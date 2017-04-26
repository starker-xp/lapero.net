<?php

namespace Starkerxp\StructureBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratorEntiteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('starkerxp:generate:entite')
            ->addArgument('entite', InputArgument::REQUIRED, "L'entite à ajouter")
            ->setDescription('Génère le gabarit pour travailler avec les entites');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = $this->getContainer()->get("starkerxp_structure.generator.entite_generator");
        $generator->generate($input->getArgument('entite'));
    }
    
}
