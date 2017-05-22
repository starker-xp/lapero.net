<?php

namespace Starkerxp\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\Entity;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * RoleUtilisateur
 *
 * @ORM\Table(name="utilisateur_role")
 * @ORM\Entity()
 */
class RoleUtilisateur extends Entity implements RoleInterface
{

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array", nullable=false)
     */
    protected $roles;

    /**
     * RoleUtilisateur constructor.
     * @param array $roles
     */
    public function __construct($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getRole()
    {
        return $this->roles;
    }


}
