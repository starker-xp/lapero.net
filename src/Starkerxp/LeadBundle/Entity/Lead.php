<?php

namespace Starkerxp\LeadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\AbstractUser;

/**
 * Lead
 *
 * @ORM\Table(name="lead", indexes={
 *  @ORM\Index(columns={"origin", "external_reference"}),
 *  @ORM\Index(columns={"external_reference"}),
 *  @ORM\Index(columns={"uuid"}),
 *  @ORM\Index(columns={"product"}),
 *  @ORM\Index(columns={"date_event"}),
 *  @ORM\Index(columns={"created_at"}),
 *  @ORM\Index(columns={"updated_at"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\LeadBundle\Repository\LeadRepository")
 */
class Lead extends AbstractUser
{
    use \Starkerxp\StructureBundle\Entity\ArchiveTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=255, nullable=false)
     */
    protected $origin;

    /**
     * @var string
     *
     * @ORM\Column(name="external_reference", type="string", length=255, nullable=true)
     */
    protected $externalReference;

    /**
     * @var string
     *
     * @ORM\Column(name="product", type="string", length=255, nullable=false)
     */
    protected $product;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="date_event", type="datetime", nullable=false)
     */
    protected $dateEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=255, nullable=true)
     */
    protected $ipAddress;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_pixel", type="boolean", nullable=true, options={"default":0})
     */
    protected $pixel;

    /**
     * Permit to stock other datas.
     * @var LeadSerialisation
     * @ORM\ManyToOne(targetEntity="LeadSerialisation", cascade="persist")
     * @ORM\JoinColumn(name="serialisation_id", referencedColumnName="id")
     */
    protected $serialisation;

    /*
    protected $firstname;

    protected $lastname;

    protected $email;

    protected $mobile;

    protected $phoneNumber;

    protected $address;

    protected $city;

    protected $country;

    protected $gender;

    protected $birthday;
     */

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set origin
     *
     * @param string $origin
     *
     * @return Lead
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get externalReference
     *
     * @return string
     */
    public function getExternalReference()
    {
        return $this->externalReference;
    }

    /**
     * Set externalReference
     *
     * @param string $externalReference
     *
     * @return Lead
     */
    public function setExternalReference($externalReference)
    {
        $this->externalReference = $externalReference;

        return $this;
    }

    /**
     * Get product
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set product
     *
     * @param string $product
     *
     * @return Lead
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get dateEvent
     *
     * @return \DateTime
     */
    public function getDateEvent()
    {
        return $this->dateEvent;
    }

    /**
     * Set dateEvent
     *
     * @param \DateTime $dateEvent
     *
     * @return Lead
     */
    public function setDateEvent($dateEvent)
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return Lead
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get pixel
     *
     * @return boolean
     */
    public function isPixel()
    {
        return $this->pixel;
    }

    /**
     * Set pixel
     *
     * @param boolean $pixel
     *
     * @return Lead
     */
    public function setPixel($pixel)
    {
        $this->pixel = $pixel;

        return $this;
    }

    /**
     * Get archive
     *
     * @return boolean
     */
    public function isArchive()
    {
        return $this->archive;
    }

    /**
     * Get serialisation
     *
     * @return array|LeadSerialisation
     */
    public function getSerialisation()
    {
        if (empty($this->serialisation)) {
            return [];
        }

        return $this->serialisation->getSerialisation();
    }

    /**
     * Set serialisation
     *
     * @param \Starkerxp\LeadBundle\Entity\LeadSerialisation $serialisation
     *
     * @return Lead
     */
    public function setSerialisation(\Starkerxp\LeadBundle\Entity\LeadSerialisation $serialisation = null)
    {
        $this->serialisation = $serialisation;

        return $this;
    }
}
