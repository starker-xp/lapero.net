<?php

namespace Starkerxp\CampagneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UtilisateurEntity;

/**
 * Campagne.
 *
 * @ORM\Table(name="campagne", indexes={
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
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @ORM\OneToMany(
     *      targetEntity="Event",
     *      mappedBy="campagne"
     * )
     */
    protected $events;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Cible",
     *      mappedBy="campagne"
     * )
     */
    protected $clients;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clients = new \Doctrine\Common\Collections\ArrayCollection();
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
}
