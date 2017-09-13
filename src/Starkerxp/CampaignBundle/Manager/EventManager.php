<?php

namespace Starkerxp\CampaignBundle\Manager;

use Starkerxp\CampaignBundle\Entity\Event;
use Starkerxp\StructureBundle\Entity\AbstractEntity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class EventManager extends AbstractManager
{

    public function getSupport(AbstractEntity $object)
    {
        return $object instanceof Event;
    }

    public function toArray(Event $object, $fields = [])
    {
        $array = [
            "id" => $object->getId(),
            "campaign_id" => $object->getCampaign()->getId(),
            "template_id" => $object->getTemplate()->getId(),
        ];

        return $this->exportFields($array, $fields);
    }
}
