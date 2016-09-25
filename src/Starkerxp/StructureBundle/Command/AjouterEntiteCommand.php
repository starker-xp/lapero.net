<?php

namespace Starkerxp\StructureBundle\Command;


use Starkerxp\StructureBundle\Generator\EntiteGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AjouterEntiteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('starkerxp:generate:entite')
            ->addArgument('entite', InputArgument::REQUIRED, "L'entite à ajouter")
            ->setDescription('Génère le gabarit pour travailler avec les entites');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entite = explode(':', $input->getArgument('entite'));
        $kernel = $this->getContainer()->get('kernel');
        $generator = new EntiteGenerator();
        $generator->setSkeletonDirs(
            $kernel->getBundle("StarkerxpStructureBundle")->getPath().'/Resources/views/Gabarit'
        );
        $generator->generate($kernel->getBundle($entite[0]), ucfirst($entite[1]));

    }


}