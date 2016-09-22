<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\Campagne;
use Starkerxp\StructureBundle\Manager\AbstractManager;
use Starkerxp\StructureBundle\Manager\Exception\ObjectClassNotAllowedException;

class CampagneManager extends AbstractManager
{

    public function insert($object)
    {
        if (!$object instanceof Campagne) {
            throw new ObjectClassNotAllowedException();
        }
        parent::insert($object);
    }

    public function update($object)
    {
        if (!$object instanceof Campagne) {
            throw new ObjectClassNotAllowedException();
        }
        parent::update($object);
    }

}
