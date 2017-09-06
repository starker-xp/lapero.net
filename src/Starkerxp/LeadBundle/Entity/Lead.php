<?php

namespace Starkerxp\LeadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UserArchiveEntity;
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
class Lead extends UserArchiveEntity implements EntityReadOnlyInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="product", type="string", length=255, nullable=false)
     */
    protected $product;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=255, nullable=false)
     */
    protected $origin;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="date_event", type="datetime", nullable=false)
     */
    protected $dateEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_adress", type="string", length=255, nullable=true)
     */
    protected $ipAdress;
}
