<?php

namespace Starkerxp\CampagneBundle\Tests\Manager;

use Starkerxp\CampagneBundle\Manager\TemplateManager;

class TemplateManagerTest extends \Starkerxp\StructureBundle\Tests\WebTest
{

    /** @var  TemplateManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campagne.manager.template');
    }

    /**
     * @group template
     * @group manager
     */
    public function testFindAll()
    {
        
    }

    /**
     * @group template
     * @group manager
     */
    public function testInsertNewCampagne()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml',]);
    }

    /**
     * @group template
     * @group manager
     */
    public function testUpdateCampagne()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml',]);
    }

}
