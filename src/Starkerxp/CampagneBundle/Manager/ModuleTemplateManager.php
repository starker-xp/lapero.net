<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\ModuleTemplate;
use Starkerxp\StructureBundle\Manager\AbstractManager;
use Starkerxp\StructureBundle\Manager\Exception\ObjectClassNotAllowedException;

class ModuleTemplateManager extends AbstractManager
{

    public function insert($object)
    {
        if (!$object instanceof ModuleTemplate) {
            throw new ObjectClassNotAllowedException();
        }
        parent::insert($object);
    }

    public function update($object)
    {
        if (!$object instanceof ModuleTemplate) {
            throw new ObjectClassNotAllowedException();
        }
        parent::update($object);
    }

}
