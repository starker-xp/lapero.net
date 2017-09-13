<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 10/05/2017
 * Time: 02:08
 */

namespace Starkerxp\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class TimestampEntity implements TimestampInterface
{
    use \Starkerxp\StructureBundle\Entity\TimestampTrait;
}
