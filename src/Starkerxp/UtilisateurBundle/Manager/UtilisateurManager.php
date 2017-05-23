<?php

namespace Starkerxp\UtilisateurBundle\Manager;

use Doctrine\ORM\EntityManager;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;
use Starkerxp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UtilisateurManager extends AbstractManager
{
    /**
     * @var UserPasswordEncoder
     */
    private $serviceEncoder;

    public function __construct(EntityManager $entityManager, $entity, $serviceEncoder)
    {
        parent::__construct($entityManager, $entity);
        $this->serviceEncoder = $serviceEncoder;
    }

    public function getSupport(Entity $object)
    {
        return $object instanceof Utilisateur;
    }

    protected function preInsert(Utilisateur $utilisateur)
    {
        if ($utilisateur->getPlainPassword()) {
            $this->modifierLeMotDePasse($utilisateur);
        }
    }

    private function modifierLeMotDePasse(Utilisateur $utilisateur)
    {
        $motDePasse = $this->serviceEncoder->encodePassword($utilisateur, $utilisateur->getPlainPassword());
        $utilisateur->setPassword($motDePasse);
    }

}
