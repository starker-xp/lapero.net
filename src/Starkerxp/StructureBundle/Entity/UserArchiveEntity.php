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
    protected $isArchive = false;
    
    public function getIsArchive()
    {
        return $this->isArchive;
    }

    public function setIsArchive($isArchive)
    {
        $this->isArchive = $isArchive;
    }
}
