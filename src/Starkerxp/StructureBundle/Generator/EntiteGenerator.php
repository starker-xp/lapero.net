<?php
namespace Starkerxp\StructureBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;

class EntiteGenerator extends Generator
{
    public function generate($namespace, $dir, $entity)
    {
        $parameters = array(
            'nomEntity'       => $entity,
            'namespace'       => $namespace,
            'namespaceBundle' => "@".str_replace("\\", "", $namespace),
            'namespaceFQC'    => str_replace("\\", "\\\\", $namespace),
        );
        $parameters["nomService"] = strtolower(
            str_replace(["_Bundle", "@"], "", preg_replace('#\B([A-Z])#', '_\1', $parameters["namespaceBundle"]))
        );
        $fichiers = $this->getFichiers();
        foreach ($fichiers as $fichier) {
            $realPathFichier = realpath(__DIR__."/../Resources/views/Gabarit").$fichier.".twig";
            if (!file_exists($realPathFichier)) {
                throw new \Exception("Il manque un fichier de template => $realPathFichier");
            }
            $target = $dir.str_replace(['_nomEntity_', '_lnomEntity_'], [$entity, lcfirst($entity)], $fichier);
            if (file_exists($target)) {
                continue;
            }
            $this->renderFile($fichier.".twig", $target, $parameters);
        }
    }

    public function getFichiers()
    {
        return [
            //'/Resources/config/managers.yml', @TODO Gestion de l'ajout de la configuration à la fin du fichier
            '/Entity/_nomEntity_.php',
            '/Manager/_nomEntity_Manager.php',
            '/Repository/_nomEntity_Repository.php',
            '/Tests/Manager/_nomEntity_ManagerTest.php',
            '/Tests/DataFixtures/_nomEntity_Manager/_nomEntity_Manager.yml',
            '/Tests/DataFixtures/_nomEntity_Manager/Default_nomEntity_.yml',
        ];
    }
}