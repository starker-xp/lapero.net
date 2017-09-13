<?php

namespace Starkerxp\CampaignBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\AbstractUser;

/**
 * Template.
 *
 * @ORM\Table(name="template", indexes={
 *  @ORM\Index(columns={"type"}),
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\CampaignBundle\Repository\TemplateRepository")
 */
class Template extends AbstractUser
{

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", length=255)
     */
    protected $object;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    protected $message;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Event",
     *      mappedBy="template"
     * )
     */
    protected $events;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nom.
     *
     * @param string $name
     *
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get object.
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set object.
     *
     * @param string $object
     *
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Add event
     *
     * @param \Starkerxp\CampaignBundle\Entity\Event $event
     *
     * @return Template
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
}
