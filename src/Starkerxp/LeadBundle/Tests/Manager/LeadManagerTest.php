<?php

namespace Starkerxp\LeadBundle\Tests\Manager;

use Starkerxp\LeadBundle\Entity\Lead;
use Starkerxp\LeadBundle\Manager\LeadManager;
use Starkerxp\StructureBundle\Tests\WebTest;

class LeadManagerTest extends WebTest
{

    /** @var  LeadManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_lead.manager.lead');
    }

    /**
     * @group lead
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(['@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/LeadManager.yml',]);
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group lead
     * @group manager
     */
    public function testInsertNewLead()
    {
        $this->loadFixtureFiles(['@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/DefaultLead.yml',]);
        $lead = new Lead();
        $this->manager->insert($lead);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group lead
     * @group manager
     */
    public function testUpdateLead()
    {
        $this->loadFixtureFiles(['@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/DefaultLead.yml',]);
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $lead = $this->manager->findOneBy($criteria);
        $this->manager->update($lead);
        $leadPostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals(1, $leadPostUpdate->getIsError());
        $this->assertNotEmpty($leadPostUpdate->getUpdatedAt());
    }

}
