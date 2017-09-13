<?php

namespace Starkerxp\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\TimestampInterface;

/**
 * RoleUser
 *
 * @ORM\Table(name="user_role", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity()
 */
class UserRole implements TimestampInterface
{
    use \Starkerxp\StructureBundle\Entity\IdTrait;
    use \Starkerxp\StructureBundle\Entity\TimestampTrait;

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
