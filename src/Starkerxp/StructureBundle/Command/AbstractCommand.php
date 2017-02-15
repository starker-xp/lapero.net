<?php

namespace Starkerxp\StructureBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends ContainerAwareCommand
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
        $this->traitement();

        return true;
    }

    abstract public function traitement();

    protected function getRepository($entityFqdn)
    {
        return $this->getEntityManager()->getRepository($entityFqdn);
    }

    protected function getEntityManager()
    {
        return $this->getContainer()->get("doctrine")->getManager();
    }

    protected function getConnection()
    {
        return $this->getContainer()->get("doctrine")->getConnection();
    }
}
