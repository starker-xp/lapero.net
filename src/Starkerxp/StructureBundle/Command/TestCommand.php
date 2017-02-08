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
        $ymlDump = [
            "services" => [
                "starker_campagne.manager.campagne" => [
                    "class"     => "Starkerxp\\CampagneBundle\\Manager\\CampagneManager",
                    "arguments" => [
                        "@doctrine.orm.entity_manager",
                        "Starkerxp\\CampagneBundle\\Entity\\Campagne",
                    ],
                    "tags"      => [
                        "name" => "starkerxp.manager.entity",
                    ],
                ],
            ],
        ];


        $yaml = Yaml::dump($ymlDump, 3, 4, 3);
        $path = './test.yml';
        file_put_contents($path, $yaml);

        // On récupère le contenu du fichier yml.
        $ymlDump = Yaml::parse(file_get_contents($path));
        $ymlDump2 = [
            "services" => [
                "starker_campagne.manager.template" => [
                    "class"     => "Starkerxp\\CampagneBundle\\Manager\\TemplateManager",
                    "arguments" => [
                        "@doctrine.orm.entity_manager",
                        "Starkerxp\\CampagneBundle\\Entity\\Template",
                    ],
                    "tags"      => [
                        "name" => "starkerxp.manager.entity",
                    ],
                ],
            ],
        ];

        $path = './test.yml';
        file_put_contents($path, $yaml);

    }


}
