<?php

namespace Starkerxp\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Entity\UtilisateurInterface;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="Starkerxp\UtilisateurBundle\Repository\UtilisateurRepository")
 */
class Utilisateur extends Entity implements JWTUserInterface, UtilisateurInterface
{

    /**
     * @var RoleUtilisateur
     *
     * @ORM\OneToOne(targetEntity="RoleUtilisateur", cascade={"persist"})
     * @ORM\JoinColumn(name="roles", referencedColumnName="id", nullable=true)
     */
    protected $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     */
    protected $email;

    /**
     * Utilisateur constructor.
     * @param array $roles
     * @param string $email
     */
    public function __construct($email, $roles)
    {
        $this->email = $email;
    }

    public static function createFromPayload($username, array $payload)
    {
        return new self(
            $username,
            $payload['roles']
        );
    }


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

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
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



    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }


}
