<?php

namespace Starkerxp\CampagneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UtilisateurEntity;

/**
 * Event
 *
 * @ORM\Table(name="campagne_event", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\CampagneBundle\Repository\EventRepository")
 */
class Event extends UtilisateurEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Campagne
     *
     * @ORM\ManyToOne(targetEntity="Campagne", cascade="persist", inversedBy="events")
     * @ORM\JoinColumn(name="campagne_id", referencedColumnName="id", nullable=false)
     */
    protected $campagne;

    /**
     * @var Template
     *
     * @ORM\ManyToOne(targetEntity="Template", cascade="persist", inversedBy="events")
     * @ORM\JoinColumn(name="template_id", referencedColumnName="id", nullable=false)
     */
    protected $template;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get campagne
     *
     * @return \Starkerxp\CampagneBundle\Entity\Campagne
     */
    public function getCampagne()
    {
        return $this->campagne;
    }

    /**
     * Set campagne
     *
     * @param \Starkerxp\CampagneBundle\Entity\Campagne $campagne
     *
     */
    public function setCampagne(\Starkerxp\CampagneBundle\Entity\Campagne $campagne)
    {
        $this->campagne = $campagne;

    }

    /**
     * Set template
     *
     * @param \Starkerxp\CampagneBundle\Entity\Template $template
     *
     * @return Event
     */
    public function setTemplate(\Starkerxp\CampagneBundle\Entity\Template $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return \Starkerxp\CampagneBundle\Entity\Template
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
