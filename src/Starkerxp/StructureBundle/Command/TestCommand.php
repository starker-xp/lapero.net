<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 07/02/2017
 * Time: 00:40
 */

namespace Starkerxp\StructureBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

//I'm includng the yml dumper. Then :

class TestCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('test');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $metadata = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata("StarkerxpCampagneBundle:Template2")
           ;
        dump($metadata);

    }


}
