<?php

namespace Starkerxp\CampaignBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\AbstractUser;

/**
 * Campaign.
 *
 * @ORM\Table(name="campaign", indexes={
 *  @ORM\Index(columns={"uuid"}),
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"}),
 *  @ORM\Index(columns={"name"}),
 *  @ORM\Index(columns={"deleted"}),
 *  @ORM\Index(columns={"status"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\CampaignBundle\Repository\CampaignRepository")
 */
class Campaign extends AbstractUser
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
     *      mappedBy="campaign"
     * )
     */
    protected $events;

    /**
     * @ORM\OneToMany(
     *      targetEntity="CampaignTarget",
     *      mappedBy="campaign"
     * )
     */
    protected $targets;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->targets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = self::DRAFT;
        $this->deleted = false;
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
     * @param \Starkerxp\CampaignBundle\Entity\Event $event
     *
     * @return Campaign
     */
    public function addEvent(\Starkerxp\CampaignBundle\Entity\Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \Starkerxp\CampaignBundle\Entity\Event $event
     */
    public function removeEvent(\Starkerxp\CampaignBundle\Entity\Event $event)
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
     * Add target
     *
     * @param \Starkerxp\CampaignBundle\Entity\CampaignTarget $target
     *
     * @return Campaign
     */
    public function addTarget(\Starkerxp\CampaignBundle\Entity\CampaignTarget $target)
    {
        $this->targets[] = $target;

        return $this;
    }

    /**
     * Remove target
     *
     * @param \Starkerxp\CampaignBundle\Entity\CampaignTarget $target
     */
    public function removeTarget(\Starkerxp\CampaignBundle\Entity\CampaignTarget $target)
    {
        $this->targets->removeElement($target);
    }

    /**
     * Get targets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTargets()
    {
        return $this->targets;
    }
}
