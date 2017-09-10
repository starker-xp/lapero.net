<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 10/05/2017
 * Time: 02:24
 */

namespace Starkerxp\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}
