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

    public function nomLocker()
    {
        return $this->getName();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $lockHandler = new LockHandler($this->nomLocker());
        if (!$lockHandler->lock()) {
            $this->output->writeln('<error>Commande déjà lancée !</error>');

            return false;
        }
        $this->executeWithLocker($input, $output);
    }

    abstract public function traitement();
}
