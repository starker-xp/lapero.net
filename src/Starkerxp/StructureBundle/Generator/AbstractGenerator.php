<?php

namespace Starkerxp\StructureBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
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

    /**
     * @param KernelInterface $kernel
     * @return AbstractGenerator
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;

        return $this;
    }

    protected function traiterLesFichiers($bundle, $parameters, $search, $replace)
    {
        foreach ($this->getFichiers() as $template) {
            try {
                $this->kernel->locateResource("@StarkerxpStructureBundle/Resources/views/Gabarit/".$template.".twig");
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException("Il manque un fichier de template ".$template);
            }
            $fichierACreerModifier = $bundle->getPath().str_replace(
                    $search,
                    $replace,
                    $template
                );
            $this->traiterLeFichier($template, $fichierACreerModifier, $parameters);
        }
    }

    public abstract function getFichiers();

    public function traiterLeFichier($fichier, $target, $parameters)
    {
        if (file_exists($target) && explode('.', basename($target))[1] == 'yml') {
            $currentServices = Yaml::parse(file_get_contents($target));
            $newServices = Yaml::parse($this->render($fichier.'.twig', $parameters));
            // gestion de fichiers sans services ni parameters => routing
            $listeDesParametres = !empty($currentServices['parameters']) ? array_keys($currentServices['parameters']) : [];
            $listeDesServices = !empty($currentServices['services']) ? array_keys($currentServices['services']) : [];
            $listeDesDeclarations = !empty($currentServices) ? array_keys($currentServices) : [];
            $listeDesNouveauxParametres = !empty($newServices['parameters']) ? array_keys($newServices['parameters']) : [];
            $listeDesNouveauxServices = !empty($newServices['services']) ? array_keys($newServices['services']) : [];
            $listeDesNouvellesDeclarations = !empty($newServices) ? array_keys($newServices) : [];

            $diffServices = array_diff($listeDesNouveauxServices, $listeDesServices);
            $diffParametres = array_diff($listeDesNouveauxParametres, $listeDesParametres);
            $diffDeclarations = array_diff($listeDesNouvellesDeclarations, $listeDesDeclarations);
            if (empty($diffServices) && empty($diffParametres) && empty($diffDeclarations)) {
                return false;
            }
            foreach ($diffServices as $libelle) {
                $currentServices['services'][$libelle] = $newServices['services'][$libelle];
            }
            foreach ($diffParametres as $libelle) {
                $currentServices['parameters'][$libelle] = $newServices['parameters'][$libelle];
            }
            foreach ($diffDeclarations as $libelle) {
                $currentServices[$libelle] = $newServices[$libelle];
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
        if (file_exists($target)) {
            return false;
        }
        $this->renderFile($fichier.'.twig', $target, $parameters);

        return true;
    }

    protected function genererParameters($clef, $controller)
    {
        $controller = explode(':', $controller);
        $bundle = $this->kernel->getBundle($controller[0]);
        $libelle = ucfirst($controller[1]);

        $clef = ucfirst(strtolower($clef));
        $parameters = [
            'nom'.$clef => $libelle,
            'nom'.$clef.'Camelize' => preg_replace('#\B([A-Z])#', '_\1', $libelle),
            'namespace'.$clef => $bundle->getNamespace(),
            'namespace'.$clef.'Bundle' => '@'.$bundle->getName(),
            'namespace'.$clef.'FQC' => str_replace('\\', '\\\\', $bundle->getNamespace()),
        ];
        $parameters['nomService'.$clef] = strtolower(
            str_replace(['_Bundle', '@'], '', preg_replace('#\B([A-Z])#', '_\1', $parameters['namespace'.$clef.'Bundle']))
        );

        return $parameters;

    }

}
