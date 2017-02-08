<?php

namespace Starkerxp\StructureBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratorControllerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('starkerxp:generate:controller')
            ->addArgument('controller', InputArgument::REQUIRED, 'Le controller à ajouter')
            ->addArgument('entite', InputArgument::REQUIRED, "L'entite à ajouter")
            ->setDescription('Génère le gabarit pour travailler avec les controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = $this->getContainer()->get("starkerxp_structure.generator.controller_generator");
        $generator->generate($input->getArgument('controller'), $input->getArgument('entite'));
    }
}
