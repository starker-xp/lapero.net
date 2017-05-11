<?php

namespace Starkerxp\CampagneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UtilisateurEntity;

/**
 * Event
 *
 * @ORM\Table(name="campagne_event")
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set campagne
     *
     * @param \Starkerxp\CampagneBundle\Entity\Campagne $campagne
     *
     * @return Event
     */
    public function setCampagne(\Starkerxp\CampagneBundle\Entity\Campagne $campagne)
    {
        $this->campagne = $campagne;

        return $this;
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
}
