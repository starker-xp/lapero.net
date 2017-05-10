<?php

namespace Starkerxp\CampagneBundle\Tests\Manager;

use Starkerxp\CampagneBundle\Entity\Event;
use Starkerxp\CampagneBundle\Manager\EventManager;
use Starkerxp\StructureBundle\Tests\WebTest;

class EventManagerTest extends WebTest
{

    /** @var  EventManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
    }

    /**
     * @group event
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/EventManager.yml',]);
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group event
     * @group manager
     */
    public function testInsertNewEvent()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',]);
        $event = new Event();
        $this->manager->insert($event);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group event
     * @group manager
     */
    public function testUpdateEvent()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',]);
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $event = $this->manager->findOneBy($criteria);
        $this->manager->update($event);
        $eventPostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals(1, $eventPostUpdate->getIsError());
        $this->assertNotEmpty($eventPostUpdate->getUpdatedAt());
    }

}
