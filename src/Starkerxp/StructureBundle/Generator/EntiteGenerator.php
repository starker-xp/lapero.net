<?php

namespace Starkerxp\StructureBundle\Generator;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EntiteGenerator extends AbstractGenerator
{
    public function generate(Bundle $bundle, $libelle)
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
            'nomEntity' => $libelle,
            'namespace' => $bundle->getNamespace(),
            'namespaceBundle' => '@'.$bundle->getName(),
            'namespaceFQC' => str_replace('\\', '\\\\', $bundle->getNamespace()),
        );
    }

    public function getClef()
    {
        return ['_nomEntity_', '_lnomEntity_'];
    }

    public function getFichiers()
    {
        return [
            '/Resources/config/managers.yml',
            '/Entity/_nomEntity_.php',
            '/Manager/_nomEntity_Manager.php',
            '/Repository/_nomEntity_Repository.php',
            '/Tests/Manager/_nomEntity_ManagerTest.php',
            '/Tests/DataFixtures/_nomEntity_Manager/_nomEntity_Manager.yml',
            '/Tests/DataFixtures/_nomEntity_Manager/Default_nomEntity_.yml',
        ];
    }
}
