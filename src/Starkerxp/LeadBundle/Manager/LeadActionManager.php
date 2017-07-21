<?php

namespace Starkerxp\LeadBundle\Manager;

use Starkerxp\LeadBundle\Entity\LeadAction;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class LeadActionManager extends AbstractManager
{
	
	public function getSupport(Entity $object)
    {
        return $object instanceof LeadAction;
    }

}
