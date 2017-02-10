<?php

namespace Starkerxp\StructureBundle\Generator;


use Doctrine\ORM\EntityManager;


class ControllerGenerator extends AbstractGenerator
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $locale;

    public function generate($controller, $entite)
    {
        // On vérifie que l'entite existe sinon une exception est levé
        $this->entityManager->getClassMetadata($entite);

        $bundle = $this->kernel->getBundle(explode(':', $controller)[0]);
        $libelle = ucfirst(explode(':', $controller)[1]);

        $parametersController = $this->genererParameters("Controller", $controller);
        $parametersEntity = $this->genererParameters("Entity", $entite);
        $parameters = array_merge($parametersController, $parametersEntity);
        $this->traiterLesFichiers($bundle, $parameters, ["_nomController_", "_lnomController_", "_locale_"], [$libelle, strtolower($libelle), $this->locale]);
    }


    public function getFichiers()
    {
        return [
            '/Controller/_nomController_Controller.php',
            '/Tests/Controller/_nomController_ControllerTest.php',
            '/Form/Type/_nomController_Type.php',
            '/Resources/config/routing.yml',  // Il faut récupérer la locale par défaut afin de générer le bon fichier.
            '/Resources/translations/_lnomController_._locale_.yml',  // Il faut récupérer la locale par défaut afin de générer le bon fichier.
        ];
    }

    /**
     * @param EntityManager $entityManager
     *
     *
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

}
