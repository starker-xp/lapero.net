<?php

namespace Starkerxp\StructureBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

abstract class LockCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $lockHandler = new LockHandler($this->getEnvironment().'_'.$this->lockerName());
        if (!$lockHandler->lock()) {
            $this->output->writeln('<error>Command already starting !</error>');

            return false;
        }
        $this->treatment();

        return true;
    }

    /**
     * @return string
     */
    public function lockerName()
    {
        return $this->getName();
    }

    abstract public function treatment();

    /**
     * @param $entityFqdn
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository($entityFqdn)
    {
        return $this->getEntityManager()->getRepository($entityFqdn);
    }

    /**
     * Récupère l'entity manager.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get("doctrine")->getManager();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getConnection()
    {
        return $this->getEntityManager()->getConnection();
    }

    /**
     * @return string
     */
    protected function getEnvironment(){
        return $this->getContainer()->get("kernel")->getEnvironment();
    }

}
