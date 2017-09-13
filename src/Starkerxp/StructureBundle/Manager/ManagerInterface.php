<?php

namespace Starkerxp\StructureBundle\Manager;

use Starkerxp\StructureBundle\Entity\AbstractEntity;

interface ManagerInterface
{
    public function insert(AbstractEntity $object);

    public function update(AbstractEntity $object);

    public function delete(AbstractEntity $object);

    public function getSupport(AbstractEntity $object);

}
