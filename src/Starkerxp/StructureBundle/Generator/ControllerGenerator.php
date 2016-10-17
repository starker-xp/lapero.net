<?php

namespace Starkerxp\StructureBundle\Generator;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ControllerGenerator extends AbstractGenerator
{

    public function getParamaters(Bundle $bundle, $libelle)
    {
        return array(
            'nomController'         => $libelle,
            'nomControllerCamelize' => preg_replace('#\B([A-Z])#', '_\1', $libelle),
            'namespace'             => $bundle->getNamespace(),
            'namespaceBundle'       => '@'.$bundle->getName(),
            'namespaceFQC'          => str_replace('\\', '\\\\', $bundle->getNamespace()),
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
        ];
    }
}
