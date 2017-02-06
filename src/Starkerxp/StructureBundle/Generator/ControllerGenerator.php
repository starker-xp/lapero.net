<?php

namespace Starkerxp\StructureBundle\Generator;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ControllerGenerator extends AbstractGenerator
{

    public function generate(Bundle $bundle, $libelle, Bundle $bundleEntite, $libelleEntite)
    {
        $parameters = $this->getParamaters($bundle, $libelle);
        $parameters['nomService'] = strtolower(
            str_replace(['_Bundle', '@'], '', preg_replace('#\B([A-Z])#', '_\1', $parameters['namespaceBundle']))
        );
        foreach ($this->getFichiers() as $fichier) {
            try {
                $this->kernel->locateResource("@StarkerxpStructureBundle/Resources/views/Gabarit/".$fichier.".twig");
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException('Il manque un fichier de template');
            }
            $target = $bundle->getPath().str_replace($this->getClef(), [$libelle, lcfirst($libelle)], $fichier);
            $this->traiterLeFichier($fichier, $target, $parameters);
        }
    }

    public function getParamaters(Bundle $bundle, $libelle)
    {
        return array(
            'nomController' => $libelle,
            'nomControllerCamelize' => preg_replace('#\B([A-Z])#', '_\1', $libelle),
            'namespace' => $bundle->getNamespace(),
            'namespaceBundle' => '@'.$bundle->getName(),
            'namespaceFQC' => str_replace('\\', '\\\\', $bundle->getNamespace()),
        );
    }

    public function getClef()
    {
        return ['_nomController_', '_lnomController_'];
    }

    public function getFichiers()
    {
        return [
            '/Controller/_nomController_Controller.php',
            '/Tests/Controller/_nomController_ControllerTest.php',
            '/Form/Type/_nomController_Type.php',
            '/Resources/config/routing.yml',  // Il faut récupérer la locale par défaut afin de générer le bon fichier.
            //'/Resources/translations/_lnomController_._defaultLocale_.yml',  // Il faut récupérer la locale par défaut afin de générer le bon fichier.
        ];
    }
}
