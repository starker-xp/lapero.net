<?php

namespace Starkerxp\StructureBundle\Command;

use Starkerxp\StructureBundle\Generator\ControllerGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AjouterRestControllerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('starkerxp:generate:rest-controller')
            ->addArgument('controller', InputArgument::REQUIRED, "Le controller à ajouter")
            ->setDescription('Génère le gabarit pour travailler avec les controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $controller = explode(':', $input->getArgument('controller'));
        $kernel = $this->getContainer()->get('kernel');
        $generator = new ControllerGenerator();
        $generator->setSkeletonDirs(
            $kernel->getBundle('StarkerxpStructureBundle')->getPath().'/Resources/views/Gabarit'
        );
        $generator->generate($kernel->getBundle($controller[0]), ucfirst($controller[1]));
    }
}
