<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\Event;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class EventManager extends AbstractManager
{

    public function getSupport(Entity $object)
    {
        return $object instanceof Event;
    }

    public function toArray(Event $object, $fields = [])
    {
        $array = [
            "id"       => $object->getId(),
            "campagne_id" => $object->getCampagne()->getId(),
            "template_id" => $object->getTemplate()->getId(),
        ];
        return $this->exportFields($array, $fields);
    }
}
