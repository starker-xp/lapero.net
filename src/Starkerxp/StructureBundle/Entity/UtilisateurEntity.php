<?php

namespace Starkerxp\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class UtilisateurEntity extends Entity
{

    /**
     * @var \Starkerxp\StructureBundle\Entity\UtilisateurInterface
     *
     * @ORM\ManyToOne(targetEntity="\Starkerxp\StructureBundle\Entity\UtilisateurInterface", cascade="persist")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id", nullable=false)
     */
    protected $utilisateur;

    /**
     * Get utilisateur
     *
     * @return \Starkerxp\StructureBundle\Entity\UtilisateurInterface
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set UtilisateurInterface
     *
     * @param \Starkerxp\StructureBundle\Entity\UtilisateurInterface $utilisateur
     *
     */
    public function setUtilisateur(\Starkerxp\StructureBundle\Entity\UtilisateurInterface $utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }


}
