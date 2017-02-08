<?php

namespace Starkerxp\StructureBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractGenerator extends Generator
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * AbstractGenerator constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public abstract function getFichiers();

    public function traiterLeFichier($fichier, $target, $parameters)
    {
        if (file_exists($target) && explode('.', basename($target))[1] == 'yml') {
            $currentServices = Yaml::parse(file_get_contents($target));
            $newServices = Yaml::parse($this->render($fichier.'.twig', $parameters));
            $listeDesParametres = !empty($currentServices['parameters'])?array_keys($currentServices['parameters']):[];
            $listeDesServices = !empty($currentServices['services'])?array_keys($currentServices['services']):[];
            $listeDesNouveauxParametres = !empty($newServices['parameters'])?array_keys($newServices['parameters']):[];
            $listeDesNouveauxServices = !empty($newServices['services'])?array_keys($newServices['services']):[];
            $diffServices = array_diff($listeDesNouveauxServices, $listeDesServices);
            $diffParametres = array_diff($listeDesNouveauxParametres, $listeDesParametres);
            if(empty($diffServices) && empty($diffParametres) ){
                return false;
            }
            foreach ($diffServices as $libelle){
                $currentServices['services'][$libelle] = $newServices['services'][$libelle];
            }
            foreach ($diffParametres as $libelle){
                $currentServices['parameters'][$libelle] = $newServices['parameters'][$libelle];
            }
            $content = Yaml::dump($currentServices, 3, 4);
            $flink = fopen($target, 'w');
            if ($flink) {
                $write = fwrite($flink, $content);
                if ($write) {
                    fclose($flink);
                }
            }
            return true;
        }
        // On ne modifie pas un fichier existant.
        if(file_exists($target)){
            return false;
        }
        $this->renderFile($fichier.'.twig', $target, $parameters);
        return true;
    }

    /**
     * @param KernelInterface $kernel
     * @return AbstractGenerator
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;

        return $this;
    }


}
