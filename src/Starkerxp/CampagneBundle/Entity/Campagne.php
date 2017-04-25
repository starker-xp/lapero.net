<?php

namespace Starkerxp\CampagneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\Entity;

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
class Campagne extends Entity
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
     * @ORM\Column(name="deleted", type="boolean", nullable=true)
     */
    protected $deleted = false;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    protected $status;

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
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
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
     * Get deleted.
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
