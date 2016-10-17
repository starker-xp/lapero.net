<?php

namespace Starkerxp\StructureBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractGenerator extends Generator
{

    public function generate(Bundle $bundle, $libelle)
    {
        $parameters = $this->getParamaters($bundle, $libelle);
        $parameters['nomService'] = strtolower(
            str_replace(['_Bundle', '@'], '', preg_replace('#\B([A-Z])#', '_\1', $parameters['namespaceBundle']))
        );
        foreach ($this->getFichiers() as $fichier) {
            $realPathFichier = realpath(__DIR__.'/../Resources/views/Gabarit').$fichier.'.twig';
            if (!file_exists($realPathFichier)) {
                throw new \Exception("Il manque un fichier de template => $realPathFichier");
            }
            $target = $bundle->getPath().str_replace($this->getClef(), [$libelle, lcfirst($libelle)], $fichier);
            $this->traiterLeFichier($fichier, $target, $parameters);
        }
    }

    public function traiterLeFichier($fichier, $target, $parameters)
    {
        if (file_exists($target) && explode('.', basename($target))[1] == 'yml') {
            $currentServices = Yaml::parse(file_get_contents($target));
            $newServices = Yaml::parse($this->render($fichier.'.twig', $parameters));
            if (empty($newServices['services'])) {
                return;
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
        $this->renderFile($fichier.'.twig', $target, $parameters);
    }
}