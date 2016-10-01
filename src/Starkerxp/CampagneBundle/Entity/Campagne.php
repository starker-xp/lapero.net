<?php

namespace Starkerxp\CampagneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\Entity;

/**
 * Campagne.
 *
 * @ORM\Table(name="campagne", indexes={
 *  @ORM\Index(columns={"type"}),
 *  @ORM\Index(columns={"ready"}),
 *  @ORM\Index(columns={"error"}),
 *  @ORM\Index(columns={"deleted"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\CampagneBundle\Repository\CampagneRepository")
 */
class Campagne extends Entity
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
     * @var bool
     *
     * @ORM\Column(name="ready", type="boolean", nullable=true)
     */
    protected $isReady = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="error", type="boolean", nullable=true)
     */
    protected $isError = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=true)
     */
    protected $isDeleted = false;

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
     * @return Campagne
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get isReady.
     *
     * @return bool
     */
    public function getIsReady()
    {
        return $this->isReady;
    }

    /**
     * Set isReady.
     *
     * @param bool $isReady
     *
     * @return Campagne
     */
    public function setIsReady($isReady)
    {
        $this->isReady = $isReady;

        return $this;
    }

    /**
     * Get isError.
     *
     * @return bool
     */
    public function getIsError()
    {
        return $this->isError;
    }

    /**
     * Set isError.
     *
     * @param bool $isError
     *
     * @return Campagne
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;

        return $this;
    }

    /**
     * Get isDeleted.
     *
     * @return bool
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isDeleted.
     *
     * @param bool $isDeleted
     *
     * @return Campagne
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
