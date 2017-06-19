<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\CampagneCible;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class CampagneCibleManager extends AbstractManager
{
	
	public function getSupport(Entity $object)
    {
        return $object instanceof CampagneCible;
    }

}
