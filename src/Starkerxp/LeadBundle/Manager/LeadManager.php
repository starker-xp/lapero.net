<?php

namespace Starkerxp\LeadBundle\Manager;

use Starkerxp\LeadBundle\Entity\Lead;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;
use Starkerxp\StructureBundle\Manager\Exception\DeleteObjectNotAllowedException;
use Starkerxp\StructureBundle\Manager\Exception\UpdateObjectNotAllowedException;

class LeadManager extends AbstractManager
{

    public function getSupport(Entity $object)
    {
        return $object instanceof Lead;
    }

    /**
     * @param Entity $object
     * @throws DeleteObjectNotAllowedException
     */
    public function delete(Entity $object)
    {
        throw new DeleteObjectNotAllowedException();
    }

    /**
     * @param Entity $object
     * @return bool|Entity|void
     * @throws UpdateObjectNotAllowedException
     */
    public function update(Entity $object)
    {
        throw new UpdateObjectNotAllowedException();
    }
}
