<?php

namespace Starkerxp\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\Entity;

/**
 * RoleUser
 *
 * @ORM\Table(name="user_role", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity()
 */
class UserRole extends Entity
{

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array", nullable=false)
     */
    protected $role;

    /**
     * RoleUser constructor.
     * @param array $roles
     */
    public function __construct($roles)
    {
        $this->role = $roles;
    }

    /**
     * @return array
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param array $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

}
