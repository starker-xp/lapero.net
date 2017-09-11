<?php

namespace Starkerxp\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class UserArchiveEntity extends UserEntity implements ArchiveInterface
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
