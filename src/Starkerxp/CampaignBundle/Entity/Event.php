<?php

namespace Starkerxp\CampaignBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\UserEntity;

/**
 * Event
 *
 * @ORM\Table(name="campaign_event", indexes={
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\CampaignBundle\Repository\EventRepository")
 */
class Event extends UserEntity
{

    /**
     * @var Campaign
     *
     * @ORM\ManyToOne(targetEntity="Campaign", cascade="persist", inversedBy="events")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=false)
     */
    protected $campaign;

    /**
     * @var Template
     *
     * @ORM\ManyToOne(targetEntity="Template", cascade="persist", inversedBy="events")
     * @ORM\JoinColumn(name="template_id", referencedColumnName="id", nullable=false)
     */
    protected $template;


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
     */
    public function setCampaign(\Starkerxp\CampaignBundle\Entity\Campaign $campaign)
    {
        $this->campaign = $campaign;

    }

    /**
     * Set template
     *
     * @param \Starkerxp\CampaignBundle\Entity\Template $template
     *
     * @return Event
     */
    public function setTemplate(\Starkerxp\CampaignBundle\Entity\Template $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return \Starkerxp\CampaignBundle\Entity\Template
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
