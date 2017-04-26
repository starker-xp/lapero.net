<?php

namespace Starkerxp\StructureBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

abstract class LockCommand extends AbstractCommand
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
        $lockHandler = new LockHandler($this->nomLocker());
        if (!$lockHandler->lock()) {
            $this->output->writeln('<error>Commande déjà lancée !</error>');

            return false;
        }
        $this->traitement();
        return true;
    }

    /**
     * @return string
     */
    public function nomLocker()
    {
        return $this->getName();
    }

}
