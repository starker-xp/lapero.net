<?php

namespace Starkerxp\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\TimestampEntity;
use Starkerxp\StructureBundle\Entity\UtilisateurInterface;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="Starkerxp\UtilisateurBundle\Repository\UtilisateurRepository")
 */
class Utilisateur extends TimestampEntity implements UtilisateurInterface
{
    use \Starkerxp\StructureBundle\Entity\UuidTrait;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

}
