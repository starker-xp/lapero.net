<?php

namespace Starkerxp\CampaignBundle\Manager;

use Starkerxp\CampaignBundle\Entity\CampaignTarget;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class CampaignTargetManager extends AbstractManager
{
	
	public function getSupport(Entity $object)
    {
        return $object instanceof CampaignTarget;
    }

}
