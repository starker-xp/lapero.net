<?php

namespace Starkerxp\StructureBundle\Manager;

use Starkerxp\StructureBundle\Entity\Entity;

interface ManagerInterface
{
    public function insert(Entity $object);

    public function update(Entity $object);

    public function delete(Entity $object);

    public function getSupport(Entity $object);

}
