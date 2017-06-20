<?php

namespace Starkerxp\StructureBundle\DataFixtures;

use Ramsey\Uuid\Uuid;

class UuidProvider
{
    public function uuid()
    {
        $uuid = Uuid::uuid4();

        return $uuid->toString();
    }
}
