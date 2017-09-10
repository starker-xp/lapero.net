<?php

namespace Starkerxp\LeadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Serialisation
 * @ORM\Table(name="lead_serialisation")
 * @ORM\Entity()
 */
class LeadSerialisation
{
    use \Starkerxp\StructureBundle\Entity\IdTrait;

    /**
     * @var array
     *
     * @ORM\Column(name="serialisation", type="json_array")
     */
    protected $serialisation;

    /**
     * Get serialisation
     *
     * @return array
     */
    public function getSerialisation()
    {
        return $this->serialisation;
    }

    /**
     * Set serialisation
     *
     * @param array $serialisation
     * @return LeadSerialisation
     */
    public function setSerialisation($serialisation)
    {
        $this->serialisation = $serialisation;

        return $this;
    }

}
