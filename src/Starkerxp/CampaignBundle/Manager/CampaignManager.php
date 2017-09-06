<?php

namespace Starkerxp\CampaignBundle\Manager;

use Starkerxp\CampaignBundle\Entity\Campaign;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class CampaignManager extends AbstractManager
{
    public function getSupport(Entity $object)
    {
        return $object instanceof Campaign;
    }

    public function toArray(Campaign $object, $fields = [])
    {
        $array = [
            "id"     => $object->getId(),
            "name"   => $object->getName(),
            "status" => $object->getStatus(),
        ];

        return $this->exportFields($array, $fields);
    }

}
