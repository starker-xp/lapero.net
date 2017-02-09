<?php

namespace Starkerxp\StructureBundle\Generator;


use Doctrine\ORM\EntityManager;

class ControllerGenerator extends AbstractGenerator
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function generate($controller, $entite)
    {
        // On vérifie que l'entite existe sinon une exception est levé
        $this->entityManager->getClassMetadata($entite);

        $controller = explode(':', $controller);
        $bundle = $this->kernel->getBundle($controller[0]);
        $libelle = ucfirst($controller[1]);

        $entite = explode(':', $entite);
        $bundleEntite = $this->kernel->getBundle($entite[0]);
        $libelleEntite = ucfirst($entite[1]);
        $parameters = [
            'nomController'             => $libelle,
            'nomControllerCamelize'     => preg_replace('#\B([A-Z])#', '_\1', $libelle),
            'namespaceController'       => $bundle->getNamespace(),
            'namespaceControllerBundle' => '@'.$bundle->getName(),
            'namespaceControllerFQC'    => str_replace('\\', '\\\\', $bundle->getNamespace()),

            'nomEntity'             => $libelleEntite,
            'namespaceEntity'       => $bundleEntite->getNamespace(),
            'namespaceEntityBundle' => '@'.$bundleEntite->getName(),
            'namespaceEntityFQC'    => str_replace('\\', '\\\\', $bundleEntite->getNamespace()),
        ];

        $parameters['nomServiceController'] = strtolower(
            str_replace(['_Bundle', '@'], '', preg_replace('#\B([A-Z])#', '_\1', $parameters['namespaceControllerBundle']))
        );
        $parameters['nomServiceEntity'] = strtolower(
            str_replace(['_Bundle', '@'], '', preg_replace('#\B([A-Z])#', '_\1', $parameters['namespaceEntityBundle']))
        );
        foreach ($this->getFichiers() as $template) {
            try {
                $this->kernel->locateResource("@StarkerxpStructureBundle/Resources/views/Gabarit/".$template.".twig");
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException('Il manque un fichier de template');
            }
            $fichierACreerModifier = $bundle->getPath().str_replace("_nomController_", $libelle, $template);
            $this->traiterLeFichier($template, $fichierACreerModifier, $parameters);
        }
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

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
