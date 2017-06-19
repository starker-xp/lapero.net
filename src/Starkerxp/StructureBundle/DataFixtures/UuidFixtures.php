<?php

namespace Starkerxp\StructureBundle\DataFixtures;

use Ramsey\Uuid\Uuid;

class UuidFixtures
{
    public static function uuid()
    {
        $uuid = Uuid::uuid4();

        return $uuid->toString();
    }
}
