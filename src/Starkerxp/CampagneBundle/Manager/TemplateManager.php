<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\Template;
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
            "nom"     => $object->getNom(),
            "type"    => $object->getType(),
            "sujet"   => $object->getSujet(),
            "message" => $object->getMessage(),
        ];
        if(empty($fields)){
            return $array;
        }
        $export = [];
        foreach($fields as $row){
            $export[$row] = $array[$row];
        }
        return $export;
    }
}
