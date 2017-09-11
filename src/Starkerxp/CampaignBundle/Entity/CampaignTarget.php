<?php

namespace Starkerxp\CampaignBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UserEntity;

/**
 * CampaignTarget
 *
 * @ORM\Table(name="campaign_target", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\CampaignBundle\Repository\CampaignTargetRepository")
 */
class CampaignTarget extends UserEntity
{
    //@todo remplacer cible par une gestion avec de 'lead' et 'leadAction'
    /**
     * @var bool
     *
     * @ORM\Column(name="lead_id", type="integer", length=11, nullable=true)
     */
    protected $lead;

    /**
     * @var bool
     *
     * @ORM\Column(name="lead_action_id", type="integer", length=11, nullable=true)
     */
    protected $leadAction;

    /**
     * @var Campaign
     *
     * @ORM\ManyToOne(targetEntity="Campaign", cascade="persist", inversedBy="targets")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=false)
     */
    protected $campaign;

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
     * Get dateLastExited
     *
     * @return \DateTime
     */
    public function getDateLastExited()
    {
        return $this->dateLastExited;
    }

    /**
     * Set dateLastExited
     *
     * @param \DateTime $dateLastExited
     *
     * @return CampaignTarget
     */
    public function setDateLastExited($dateLastExited)
    {
        $this->dateLastExited = $dateLastExited;

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
     * Set manuallyRemoved
     *
     * @param boolean $manuallyRemoved
     *
     * @return CampaignTarget
     */
    public function setManuallyRemoved($manuallyRemoved)
    {
        $this->manuallyRemoved = $manuallyRemoved;

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
     * Set manuallyAdded
     *
     * @param boolean $manuallyAdded
     *
     * @return CampaignTarget
     */
    public function setManuallyAdded($manuallyAdded)
    {
        $this->manuallyAdded = $manuallyAdded;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \Starkerxp\CampaignBundle\Entity\Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Set campaign
     *
     * @param \Starkerxp\CampaignBundle\Entity\Campaign $campaign
     *
     * @return CampaignTarget
     */
    public function setCampaign(\Starkerxp\CampaignBundle\Entity\Campaign $campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }
}
