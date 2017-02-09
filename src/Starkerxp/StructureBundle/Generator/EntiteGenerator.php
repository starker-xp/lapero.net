<?php

namespace Starkerxp\StructureBundle\Generator;


class EntiteGenerator extends AbstractGenerator
{
    public function generate($entite)
    {
        $entite = explode(':', $entite);
        $bundle = $this->kernel->getBundle($entite[0]);
        $libelle = ucfirst($entite[1]);

        $parameters = [
            'nomEntity'       => $libelle,
            'namespaceEntity'       => $bundle->getNamespace(),
            'namespaceEntityBundle' => '@'.$bundle->getName(),
            'namespaceEntityFQC'    => str_replace('\\', '\\\\', $bundle->getNamespace()),
        ];
        $parameters['nomServiceEntity'] = strtolower(str_replace(['_Bundle', '@'], '', preg_replace('#\B([A-Z])#', '_\1', $parameters['namespaceEntityBundle'])));

        foreach ($this->getFichiers() as $template) {
            try {
                $this->kernel->locateResource("@StarkerxpStructureBundle/Resources/views/Gabarit/".$template.".twig");
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException('Il manque un fichier de template');
            }
            // On génère le nom de fichier qui va être modifié ou généré.
            $fichierACreerModifier = $bundle->getPath().str_replace("_nomEntity_", $libelle, $template);
            $this->traiterLeFichier($template, $fichierACreerModifier, $parameters);
        }
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
