<?php

namespace Starkerxp\CampagneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UtilisateurEntity;

/**
 * Campagne.
 *
 * @ORM\Table(name="campagne", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"}),
 *  @ORM\Index(columns={"name"}),
 *  @ORM\Index(columns={"deleted"}),
 *  @ORM\Index(columns={"status"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\CampagneBundle\Repository\CampagneRepository")
 */
class Campagne extends UtilisateurEntity
{
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const SENT = 'send';
    const CANCEL = 'cancel';
    const ERROR = 'error';


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", options={"default": false})
     */
    protected $deleted;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime", nullable=true)
     */
    protected $dateStart;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Event",
     *      mappedBy="campagne"
     * )
     */
    protected $events;

    /**
     * @ORM\OneToMany(
     *      targetEntity="CampagneCible",
     *      mappedBy="campagne"
     * )
     */
    protected $cibles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cibles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = self::DRAFT;
        $this->deleted = false;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     */
    public function setName($name)
    {
        $this->name = $name;

    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get deleted.
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deleted.
     *
     * @param bool $deleted
     *
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * Add event
     *
     * @param \Starkerxp\CampagneBundle\Entity\Event $event
     *
     * @return Campagne
     */
    public function addEvent(\Starkerxp\CampagneBundle\Entity\Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \Starkerxp\CampagneBundle\Entity\Event $event
     */
    public function removeEvent(\Starkerxp\CampagneBundle\Entity\Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add cible
     *
     * @param \Starkerxp\CampagneBundle\Entity\CampagneCible $cible
     *
     * @return Campagne
     */
    public function addCible(\Starkerxp\CampagneBundle\Entity\CampagneCible $cible)
    {
        $this->cibles[] = $cible;

        return $this;
    }

    /**
     * Remove cible
     *
     * @param \Starkerxp\CampagneBundle\Entity\CampagneCible $cible
     */
    public function removeCible(\Starkerxp\CampagneBundle\Entity\CampagneCible $cible)
    {
        $this->cibles->removeElement($cible);
    }

    /**
     * Get cibles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCibles()
    {
        return $this->cibles;
    }
}
