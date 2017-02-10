<?php

namespace Starkerxp\StructureBundle\Generator;


class EntiteGenerator extends AbstractGenerator
{
    public function generate($entite)
    {
        $bundle = $this->kernel->getBundle(explode(':', $entite)[0]);
        $libelle = ucfirst(explode(':', $entite)[1]);
        $parameters = $this->genererParameters("Entity", $entite);
        $this->traiterLesFichiers($bundle, $parameters, ["_nomEntity_"], [$libelle]);
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
