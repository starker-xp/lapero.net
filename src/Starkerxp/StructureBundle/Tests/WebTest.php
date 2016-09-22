<?php

namespace Starkerxp\StructureBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class WebTest extends WebTestCase
{

    /**
     * Erase all database data.
     */
    public function setUp()
    {
        $this->loadFixtureFiles([]);
    }

}
