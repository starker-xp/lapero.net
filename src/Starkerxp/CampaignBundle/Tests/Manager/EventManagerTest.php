<?php

namespace Starkerxp\CampaignBundle\Tests\Manager;

use Starkerxp\CampaignBundle\Entity\Event;
use Starkerxp\CampaignBundle\Manager\EventManager;
use Starkerxp\StructureBundle\Test\WebTest;

class EventManagerTest extends WebTest
{

    /** @var  EventManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campaign.manager.event');
    }

    /**
     * @group event
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/EventManager/EventManager.yml',
            ]
        );
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group event
     * @group manager
     */
    public function testInsertNewEvent()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/CampaignManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );
        $campaigns = $this->getRepository("StarkerxpCampaignBundle:Campaign")->findBy([], ['id' => 'ASC']);
        $templates = $this->getRepository("StarkerxpCampaignBundle:Template")->findBy([], ['id' => 'ASC']);
        $event = new Event();
        $event->setCampaign($campaigns[1]);
        $event->setTemplate($templates[1]);
        $this->manager->insert($event);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group event
     * @group manager
     */
    public function testUpdateEvent()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $event = $this->manager->findOneBy($criteria);
        $this->manager->update($event);
        $eventPostUpdate = $this->manager->findOneBy($criteria);
        $this->assertNotEmpty($eventPostUpdate->getUpdatedAt());
    }

}
