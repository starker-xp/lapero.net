<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\Campagne;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class CampagneManager extends AbstractManager
{
    public function getSupport(Entity $object)
    {
        return $object instanceof Campagne;
    }

    public function toArray(Campagne $object, $fields = [])
    {
        $array = [
            "id"     => $object->getId(),
            "name"   => $object->getName(),
            "status" => $object->getStatus(),
        ];

        return $this->exportFields($array, $fields);
    }

}
