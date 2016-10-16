<?php

namespace Starkerxp\StructureBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Yaml\Yaml;

class ControllerGenerator extends Generator
{
    public function generate(Bundle $bundle, $controller)
    {
        $parameters = array(
            'nomController'         => $controller,
            'nomControllerCamelize' => preg_replace('#\B([A-Z])#', '_\1', $controller),
            'namespace'             => $bundle->getNamespace(),
            'namespaceBundle'       => '@'.$bundle->getName(),
            'namespaceFQC'          => str_replace('\\', '\\\\', $bundle->getNamespace()),
        );
        $parameters['nomService'] = strtolower(
            str_replace(['_Bundle', '@'], '', preg_replace('#\B([A-Z])#', '_\1', $parameters['namespaceBundle']))
        );
        $fichiers = $this->getFichiers();
        foreach ($fichiers as $fichier) {
            $realPathFichier = realpath(__DIR__.'/../Resources/views/Gabarit').$fichier.'.twig';
            if (!file_exists($realPathFichier)) {
                throw new \Exception("Il manque un fichier de template => $realPathFichier");
            }
            $path = $bundle->getPath();
            $target = $path.str_replace(['_nomController_', '_lnomController_'], [$controller, lcfirst($controller)], $fichier);
            if (file_exists($target)) {
                if (explode('.', basename($target))[1] == 'yml') {
                    $currentServices = Yaml::parse(file_get_contents($target));
                    $newServices = Yaml::parse($this->render($fichier.'.twig', $parameters));
                    if (empty($newServices['services'])) {
                        continue;
                    }
                    $listeNewServices = array_keys($newServices['services']);
                    foreach ($listeNewServices as $servicePotentiel) {
                        if (!empty($currentServices['services'][$servicePotentiel])) {
                            continue;
                        }
                        $currentServices['services'][$servicePotentiel] = $newServices['services'][$servicePotentiel];
                    }
                    $content = Yaml::dump($currentServices, 8, 4);
                    $flink = fopen($target, 'w');
                    if ($flink) {
                        $write = fwrite($flink, $content);
                        if ($write) {
                            fclose($flink);
                        }
                    }
                }
                continue;
            }
            $this->renderFile($fichier.'.twig', $target, $parameters);
        }
    }

    public function getFichiers()
    {
        return [
            '/Controller/_nomController_Controller.php',
            '/Tests/Controller/_nomController_ControllerTest.php',
        ];
    }
}
