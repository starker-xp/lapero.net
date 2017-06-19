<?php

namespace Starkerxp\CampagneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UtilisateurEntity;

/**
 * CampagneCible
 *
 * @ORM\Table(name="campagne_cible")
 * @ORM\Entity(repositoryClass="Starkerxp\CampagneBundle\Repository\CampagneCibleRepository")
 */
class CampagneCible extends UtilisateurEntity
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
     * @var bool
     *
     * @ORM\Column(name="cible_id", type="integer", length=11, nullable=false)
     */
    protected $cible;

    /**
     * @var Campagne
     *
     * @ORM\ManyToOne(targetEntity="Campagne", cascade="persist", inversedBy="clients")
     * @ORM\JoinColumn(name="campagne_id", referencedColumnName="id", nullable=false)
     */
    protected $campagne;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_last_exited", type="datetime", nullable=true)
     */
    protected $dateLastExited = null;

    /**
     * @var bool
     *
     * @ORM\Column(name="manually_removed", type="boolean", options={"default": false})
     */
    protected $manuallyRemoved = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="manually_added", type="boolean", options={"default": false})
     */
    protected $manuallyAdded = false;

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
     * Set dateLastExited
     *
     * @param \DateTime $dateLastExited
     *
     * @return CampagneCible
     */
    public function setDateLastExited($dateLastExited)
    {
        $this->dateLastExited = $dateLastExited;

        return $this;
    }

    /**
     * Get dateLastExited
     *
     * @return \DateTime
     */
    public function getDateLastExited()
    {
        return $this->dateLastExited;
    }

    /**
     * Set manuallyRemoved
     *
     * @param boolean $manuallyRemoved
     *
     * @return CampagneCible
     */
    public function setManuallyRemoved($manuallyRemoved)
    {
        $this->manuallyRemoved = $manuallyRemoved;

        return $this;
    }

    /**
     * Get manuallyRemoved
     *
     * @return boolean
     */
    public function getManuallyRemoved()
    {
        return $this->manuallyRemoved;
    }

    /**
     * Set manuallyAdded
     *
     * @param boolean $manuallyAdded
     *
     * @return CampagneCible
     */
    public function setManuallyAdded($manuallyAdded)
    {
        $this->manuallyAdded = $manuallyAdded;

        return $this;
    }

    /**
     * Get manuallyAdded
     *
     * @return boolean
     */
    public function getManuallyAdded()
    {
        return $this->manuallyAdded;
    }

    /**
     * Set cible
     *
     * @param integer $cible
     *
     * @return CampagneCible
     */
    public function setCampagneCible($cible)
    {
        $this->cible = $cible;

        return $this;
    }

    /**
     * Get cible
     *
     * @return integer
     */
    public function getCampagneCible()
    {
        return $this->cible;
    }

    /**
     * Set campagne
     *
     * @param \Starkerxp\CampagneBundle\Entity\Campagne $campagne
     *
     * @return CampagneCible
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