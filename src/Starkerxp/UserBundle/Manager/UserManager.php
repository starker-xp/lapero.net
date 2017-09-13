<?php

namespace Starkerxp\UserBundle\Manager;

use Doctrine\ORM\EntityManager;
use Starkerxp\StructureBundle\Entity\AbstractEntity;
use Starkerxp\StructureBundle\Manager\AbstractManager;
use Starkerxp\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserManager extends AbstractManager
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

    public function getSupport(AbstractEntity $object)
    {
        return $object instanceof User;
    }

    protected function preInsert(User $user)
    {
        if ($user->getPlainPassword()) {
            $this->modifierLeMotDePasse($user);
        }
    }

    private function modifierLeMotDePasse(User $user)
    {
        $motDePasse = $this->serviceEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($motDePasse);
    }

}
