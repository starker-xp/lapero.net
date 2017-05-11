<?php

namespace Starkerxp\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Entity\UtilisateurInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="Starkerxp\UtilisateurBundle\Repository\UtilisateurRepository")
 */
class Utilisateur extends Entity implements UtilisateurInterface, UserInterface
{

    /**
     * @var RoleUtilisateur
     *
     * @ORM\OneToOne(targetEntity="RoleUtilisateur", cascade={"persist"})
     * @ORM\JoinColumn(name="roles", referencedColumnName="id", nullable=false)
     */
    protected $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    public function getRoles()
    {
        /** @var \Starkerxp\UtilisateurBundle\Entity\RoleUtilisateur $roles */
        if (empty($this->roles)) {
            return null;
        }

        return $this->roles->getRoles();
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }


}
