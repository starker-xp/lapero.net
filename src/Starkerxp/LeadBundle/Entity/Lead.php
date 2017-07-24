<?php

namespace Starkerxp\LeadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UtilisateurArchiveEntity;
use Starkerxp\StructureBundle\Manager\EntityReadOnlyInterface;

/**
 * Lead
 *
 * @ORM\Table(name="lead", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\LeadBundle\Repository\LeadRepository")
 */
class Lead extends UtilisateurArchiveEntity implements EntityReadOnlyInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="provenance", type="string", length=50, nullable=false)
     */
    protected $provenance;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="date_event", type="datetime", nullable=false)
     */
    protected $dateEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse_ip", type="string", length=255, nullable=true)
     */
    protected $adresseIp;
}
