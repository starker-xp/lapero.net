<?php

namespace Starkerxp\CampagneBundle\Tests\Manager;

use Starkerxp\CampagneBundle\Entity\Campagne;
use Starkerxp\CampagneBundle\Manager\CampagneManager;

class CampagneManagerTest extends \Starkerxp\StructureBundle\Tests\WebTest
{
    /** @var CampagneManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
    }

    /**
     * @group campagne
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/CampagneManager.yml']);
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group campagne
     * @group manager
     */
    public function testInsertNewCampagne()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml']);
        $campagne = new \Starkerxp\CampagneBundle\Entity\Campagne();
        $campagne->setName('Ma super campagne');
        $campagne->setType('marketing');
        $campagne->setDeleted(false);
        $campagne->setStatus(Campagne::STATUS_DRAFT);
        $campagne->setSendAt(null);
        $this->manager->insert($campagne);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group campagne
     * @group manager
     */
    public function testUpdateCampagne()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml']);
        $criteria = ['createdAt' => new \DateTime('2016-08-05 12:12:12')];
        $campagne = $this->manager->findOneBy($criteria);
        $campagne->setStatus(Campagne::STATUS_ERROR);
        $this->manager->update($campagne);
        $campagnePostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals(Campagne::STATUS_ERROR, $campagnePostUpdate->getStatus());
        $this->assertNotEmpty($campagnePostUpdate->getUpdatedAt());
    }
}
