<?php

namespace Starkerxp\LeadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UtilisateurArchiveEntity;

/**
 * LeadAction
 *
 * @ORM\Table(name="leadaction", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\LeadBundle\Repository\LeadActionRepository")
 */
class LeadAction extends UtilisateurArchiveEntity
{

}
