<?php

namespace Starkerxp\UtilisateurBundle\Tests\Manager;

use Starkerxp\UtilisateurBundle\Entity\Utilisateur;
use Starkerxp\UtilisateurBundle\Manager\UtilisateurManager;
use Starkerxp\StructureBundle\Tests\WebTest;

class UtilisateurManagerTest extends WebTest
{

    /** @var  UtilisateurManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_utilisateur.manager.utilisateur');
    }

    /**
     * @group utilisateur
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/UtilisateurManager.yml',]);
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group utilisateur
     * @group manager
     */
    public function testInsertNewUtilisateur()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',]);
        $utilisateur = new Utilisateur();
        $this->manager->insert($utilisateur);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group utilisateur
     * @group manager
     */
    public function testUpdateUtilisateur()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',]);
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $utilisateur = $this->manager->findOneBy($criteria);
        $this->manager->update($utilisateur);
        $utilisateurPostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals(1, $utilisateurPostUpdate->getIsError());
        $this->assertNotEmpty($utilisateurPostUpdate->getUpdatedAt());
    }

}
