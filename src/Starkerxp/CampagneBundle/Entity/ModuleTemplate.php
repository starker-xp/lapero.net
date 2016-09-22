<?php

namespace Starkerxp\CampagneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Starkerxp\StructureBundle\Entity\Entity;

/**
 * Campagne
 *
 * @ORM\Table(name="template_module", indexes={
 *  @ORM\Index(columns={"code"})
 * })
 * @ORM\Entity(repositoryClass="Starkerxp\CampagneBundle\Repository\ModuleTemplateRepository")
 */
class ModuleTemplate extends Entity
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu_html", type="text")
     */
    protected $contenuHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu_txt", type="text")
     */
    protected $contenuTxt;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set code
     *
     * @param string $code
     *
     * @return ModuleTemplate
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set contenuHtml
     *
     * @param string $contenuHtml
     *
     * @return ModuleTemplate
     */
    public function setContenuHtml($contenuHtml)
    {
        $this->contenuHtml = $contenuHtml;

        return $this;
    }

    /**
     * Get contenuHtml
     *
     * @return string
     */
    public function getContenuHtml()
    {
        return $this->contenuHtml;
    }

    /**
     * Set contenuTxt
     *
     * @param string $contenuTxt
     *
     * @return ModuleTemplate
     */
    public function setContenuTxt($contenuTxt)
    {
        $this->contenuTxt = $contenuTxt;

        return $this;
    }

    /**
     * Get contenuTxt
     *
     * @return string
     */
    public function getContenuTxt()
    {
        return $this->contenuTxt;
    }
}
