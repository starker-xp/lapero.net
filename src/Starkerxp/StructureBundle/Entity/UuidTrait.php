<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 10/05/2017
 * Time: 02:24
 */

namespace Starkerxp\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait UuidTrait
{

    /**
     * @var guid
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false, unique=true)
     */
    protected $uuid;


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
     * Set uuid.
     *
     * @param guid $uuid
     *
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }
}
