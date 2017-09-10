<?php

namespace Starkerxp\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Entity extends TimestampEntity
{

    use \Starkerxp\StructureBundle\Entity\UuidTrait;
    use \Starkerxp\StructureBundle\Entity\IdTrait;

}
