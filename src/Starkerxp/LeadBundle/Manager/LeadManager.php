<?php

namespace Starkerxp\LeadBundle\Manager;

use Starkerxp\LeadBundle\Entity\Lead;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class LeadManager extends AbstractManager
{
	
	public function getSupport(Entity $object)
    {
        return $object instanceof Lead;
    }

}
