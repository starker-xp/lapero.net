<?php

namespace Starkerxp\CampaignBundle\Manager;

use Starkerxp\CampaignBundle\Entity\Event;
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
            "campaign_id" => $object->getCampaign()->getId(),
            "template_id" => $object->getTemplate()->getId(),
        ];
        return $this->exportFields($array, $fields);
    }
}
