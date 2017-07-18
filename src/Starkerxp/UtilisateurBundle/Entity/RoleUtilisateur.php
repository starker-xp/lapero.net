<?php

namespace Starkerxp\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\Entity;

/**
 * RoleUtilisateur
 *
 * @ORM\Table(name="utilisateur_role", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity()
 */
class RoleUtilisateur extends Entity
{

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array", nullable=false)
     */
    protected $role;

    /**
     * RoleUtilisateur constructor.
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
