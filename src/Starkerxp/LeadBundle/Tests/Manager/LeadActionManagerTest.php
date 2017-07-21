<?php

namespace Starkerxp\LeadBundle\Tests\Manager;

use Starkerxp\LeadBundle\Entity\LeadAction;
use Starkerxp\LeadBundle\Manager\LeadActionManager;
use Starkerxp\StructureBundle\Tests\WebTest;

class LeadActionManagerTest extends WebTest
{

    /** @var  LeadActionManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_lead.manager.leadaction');
    }

    /**
     * @group leadaction
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(['@StarkerxpLeadBundle/Tests/DataFixtures/LeadActionManager/LeadActionManager.yml',]);
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group leadaction
     * @group manager
     */
    public function testInsertNewLeadAction()
    {
        $this->loadFixtureFiles(['@StarkerxpLeadBundle/Tests/DataFixtures/LeadActionManager/DefaultLeadAction.yml',]);
        $leadaction = new LeadAction();
        $this->manager->insert($leadaction);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group leadaction
     * @group manager
     */
    public function testUpdateLeadAction()
    {
        $this->loadFixtureFiles(['@StarkerxpLeadBundle/Tests/DataFixtures/LeadActionManager/DefaultLeadAction.yml',]);
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $leadaction = $this->manager->findOneBy($criteria);
        $this->manager->update($leadaction);
        $leadactionPostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals(1, $leadactionPostUpdate->getIsError());
        $this->assertNotEmpty($leadactionPostUpdate->getUpdatedAt());
    }

}
