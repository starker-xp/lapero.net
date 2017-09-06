<?php

namespace Starkerxp\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Ramsey\Uuid\Uuid;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Entity\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\UserBundle\Repository\UserRepository")
 */
class User extends Entity implements JWTUserInterface, UserInterface
{

    /**
     * @var RoleUser
     *
     * @ORM\OneToOne(targetEntity="RoleUser", cascade={"persist"})
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
     * User constructor.
     * @param array $roles
     * @param string $email
     */
    public function __construct($email, $roles)
    {
        $this->email = $email;
        $this->setRoles($roles);
        $uuid = Uuid::uuid4();
        $this->salt = $uuid->toString();
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
        /** @var \Starkerxp\UserBundle\Entity\RoleUser $roles */
        if (empty($this->roles)) {
            return null;
        }

        return $this->roles->getRole();
    }

    /**
     * @param array $roles
     *
     * @return bool
     */
    public function setRoles($roles)
    {
        if($roles instanceof RoleUser || empty($this->roles) ){
            $this->roles = new RoleUser($roles);
            return true;
        }
        $this->roles->setRoles($roles);

        return true;
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
