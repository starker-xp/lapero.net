<?php

namespace Starkerxp\LeadBundle\Tests\Manager;

use Starkerxp\LeadBundle\Entity\Lead;
use Starkerxp\LeadBundle\Manager\LeadManager;
use Starkerxp\StructureBundle\Test\WebTest;

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
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/LeadManager.yml',
            ]
        );
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group lead
     * @group manager
     */
    public function testInsertNewLead()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/DefaultLead.yml',
            ]
        );
        $lead = new Lead();
        $lead->setOrigin("validatemy.com");
        $lead->setProduct("form");
        $lead->setDateEvent(new \DateTime("2016-05-06 00:56:45"));
        $this->manager->insert($lead);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group lead
     * @group manager
     */
    public function testUpdateLead()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/DefaultLead.yml',
            ]
        );
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $lead = $this->manager->findOneBy($criteria);
        $this->manager->update($lead);
        $leadPostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals("validatemy.com", $leadPostUpdate->getOrigin());
        $this->assertNotEmpty($leadPostUpdate->getUpdatedAt());
    }

}
