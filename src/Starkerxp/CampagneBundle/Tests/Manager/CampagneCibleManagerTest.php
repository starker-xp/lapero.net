<?php

namespace Starkerxp\CampagneBundle\Tests\Manager;

use Starkerxp\CampagneBundle\Entity\CampagneCible;
use Starkerxp\CampagneBundle\Manager\CampagneCibleManager;
use Starkerxp\StructureBundle\Tests\WebTest;

class CampagneCibleManagerTest extends WebTest
{

    /** @var  CampagneCibleManager */
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/CampagneManager.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneCibleManager/CampagneCibleManager.yml',
            ]
        );
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group cible
     * @group manager
     */
    public function testInsertNewCampagneCible()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/CampagneManager.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneCibleManager/DefaultCampagneCible.yml',
            ]
        );
        $campagnes = $this->getRepository("StarkerxpCampagneBundle:Campagne")->findBy([], ['id' => 'ASC']);
        $cible = new CampagneCible();
        $cible->setCampagne($campagnes[1]);
        $this->manager->insert($cible);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group cible
     * @group manager
     */
    public function testUpdateCampagneCible()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/CampagneManager.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneCibleManager/DefaultCampagneCible.yml',
            ]
        );
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $cible = $this->manager->findOneBy($criteria);
        $this->manager->update($cible);
        $ciblePostUpdate = $this->manager->findOneBy($criteria);
        $this->assertNotEmpty($ciblePostUpdate->getUpdatedAt());
    }

}
