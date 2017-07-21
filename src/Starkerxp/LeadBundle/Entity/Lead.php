<?php

namespace Starkerxp\LeadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UtilisateurArchiveEntity;

/**
 * Lead
 *
 * @ORM\Table(name="lead", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\LeadBundle\Repository\LeadRepository")
 */
class Lead extends UtilisateurArchiveEntity
{

}
