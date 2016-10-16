<?php

namespace Starkerxp\CampagneBundle\Repository;

use Starkerxp\CampagneBundle\Entity\Template;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class TemplateManager extends AbstractManager
{


    public function insert(Entity $object)
    {
        /**@var Template $object */
        if ($object->getType() == "email" && empty($object->getMessage())) {
            throw new \InvalidArgumentException();
        }
        parent::insert($object);

        return $object;
    }


    public function update(Entity $object)
    {
        /**@var Template $object */
        if ($object->getType() == "email" && empty($object->getMessage())) {
            throw new \InvalidArgumentException();
        }
        parent::update($object);

        return $object;
    }


    public function getSupport(Entity $object)
    {
        return $object instanceof Template;
    }

}
