<?php

namespace Starkerxp\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractUser extends AbstractEntity
{

    /**
     * @var \Starkerxp\StructureBundle\Entity\UserInterface
     *
     * @ORM\ManyToOne(targetEntity="\Starkerxp\StructureBundle\Entity\UserInterface", cascade="persist")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * Get user
     *
     * @return \Starkerxp\StructureBundle\Entity\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set UserInterface
     *
     * @param \Starkerxp\StructureBundle\Entity\UserInterface $user
     *
     */
    public function setUser(\Starkerxp\StructureBundle\Entity\UserInterface $user)
    {
        $this->user = $user;
    }

}
