<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\Template;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class TemplateManager extends AbstractManager
{

    public function getSupport(Entity $object)
    {
        if (!$object instanceof Template) {
            return false;
        }
        if ($object->getType() == 'email' && empty($object->getMessage())) {
            throw new \InvalidArgumentException();
        }

        return $object instanceof Template;
    }
}
