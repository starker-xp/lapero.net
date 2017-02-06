<?php

namespace Starkerxp\StructureBundle\Command;

use Starkerxp\StructureBundle\Generator\ControllerGenerator;
use Starkerxp\StructureBundle\Generator\EntiteGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AjouterRestControllerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('starkerxp:generate:rest-controller')
            ->addArgument('controller', InputArgument::REQUIRED, 'Le controller à ajouter')
            ->addArgument('entite', InputArgument::REQUIRED, "L'entite à ajouter")
            ->setDescription('Génère le gabarit pour travailler avec les controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $controller = explode(':', $input->getArgument('controller'));
        $entite = explode(':', $input->getArgument('entite'));

        $kernel = $this->getContainer()->get('kernel');

        $generator = new EntiteGenerator();
        $generator->setKernel($kernel);
        $generator->setSkeletonDirs([
            $kernel->getBundle('StarkerxpStructureBundle')->getPath().'/Resources/views/Gabarit',
        ]);
        $generator->generate($kernel->getBundle($entite[0]), ucfirst($entite[1]));


        $generator = new ControllerGenerator();
        $generator->setKernel($kernel);
        $generator->setSkeletonDirs([
            $kernel->getBundle('StarkerxpStructureBundle')->getPath().'/Resources/views/Gabarit',
        ]);
        $generator->generate($kernel->getBundle($controller[0]), ucfirst($controller[1]), $kernel->getBundle($entite[0]), ucfirst($entite[1]));
    }
}
