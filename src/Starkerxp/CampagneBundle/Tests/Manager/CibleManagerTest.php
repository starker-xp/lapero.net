<?php

namespace Starkerxp\CampagneBundle\Tests\Manager;

use Starkerxp\CampagneBundle\Entity\Cible;
use Starkerxp\CampagneBundle\Manager\CibleManager;
use Starkerxp\StructureBundle\Tests\WebTest;

class CibleManagerTest extends WebTest
{

    /** @var  CibleManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campagne.manager.cible');
    }

    /**
     * @group cible
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CibleManager/CibleManager.yml',
            ]
        );
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group cible
     * @group manager
     */
    public function testInsertNewCible()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CibleManager/DefaultCible.yml',
            ]
        );
        $cible = new Cible();
        $this->manager->insert($cible);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group cible
     * @group manager
     */
    public function testUpdateCible()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CibleManager/DefaultCible.yml',
            ]
        );
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $cible = $this->manager->findOneBy($criteria);
        $this->manager->update($cible);
        $ciblePostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals(1, $ciblePostUpdate->getIsError());
        $this->assertNotEmpty($ciblePostUpdate->getUpdatedAt());
    }

}
