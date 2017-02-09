<?php

namespace Starkerxp\CampagneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\Entity;

/**
 * Template.
 *
 * @ORM\Table(name="template")
 * @ORM\Entity(repositoryClass="Starkerxp\CampagneBundle\Repository\TemplateRepository")
 */
class Template extends Entity
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
     * @var guid
     *
     * @ORM\Column(name="uuid", type="guid")
     */
    protected $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $nom;

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
    protected $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    protected $message;

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
     * Set uuid.
     *
     * @param guid $uuid
     *
     * @return Template
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Get uuid.
     *
     * @return guid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Template
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Template
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * Set sujet.
     *
     * @param string $sujet
     *
     * @return Template
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    }

    /**
     * Get sujet.
     *
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     * @return Template
     */
    public function setMessage($message)
    {
        $this->message = $message;
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
}
