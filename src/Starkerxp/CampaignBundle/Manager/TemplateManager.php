<?php

namespace Starkerxp\CampaignBundle\Manager;

use Starkerxp\CampaignBundle\Entity\Template;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class TemplateManager extends AbstractManager
{

    public function getSupport(Entity $object)
    {
        return is_object($object) && $object instanceof Template;
    }

    public function toArray(Template $object, $fields = [])
    {
        $array = [
            "id"      => $object->getId(),
            "name"     => $object->getName(),
            "type"    => $object->getType(),
            "object"   => $object->getObject(),
            "message" => $object->getMessage(),
        ];

        return $this->exportFields($array, $fields);
    }
}
