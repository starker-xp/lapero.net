<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\Cible;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class CibleManager extends AbstractManager
{
	
	public function getSupport(Entity $object)
    {
        return $object instanceof Cible;
    }

}
