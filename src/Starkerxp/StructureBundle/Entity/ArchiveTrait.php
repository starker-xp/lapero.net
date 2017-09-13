<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 10/05/2017
 * Time: 02:24
 */

namespace Starkerxp\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ArchiveTrait
{

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_archive", type="boolean", nullable=true, options={"default":0})
     */
    protected $archive = false;

    public function isArchive()
    {
        return $this->archive;
    }

    public function setArchive($archive)
    {
        $this->archive = $archive;
    }
}
