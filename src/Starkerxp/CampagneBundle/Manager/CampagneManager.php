<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\Campagne;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class CampagneManager extends AbstractManager
{
    public function getSupport(Entity $object)
    {
        return $object instanceof Campagne;
    }
}
