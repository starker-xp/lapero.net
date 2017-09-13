<?php

namespace Starkerxp\CampaignBundle\Manager;

use Starkerxp\CampaignBundle\Entity\CampaignTarget;
use Starkerxp\StructureBundle\Entity\AbstractEntity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class CampaignTargetManager extends AbstractManager
{

    public function getSupport(AbstractEntity $object)
    {
        return $object instanceof CampaignTarget;
    }

}
