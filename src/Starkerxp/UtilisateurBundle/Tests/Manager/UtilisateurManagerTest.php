<?php

namespace Starkerxp\UtilisateurBundle\Tests\Manager;

use Starkerxp\StructureBundle\Tests\WebTest;
use Starkerxp\UtilisateurBundle\Entity\Utilisateur;
use Starkerxp\UtilisateurBundle\Manager\UtilisateurManager;

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
        $utilisateur = new Utilisateur('test2@yopmail.com', ["ROLE_SUPER_ADMIN"]);
        $utilisateur->setPlainPassword("azerty");
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
        $criteria = ['createdAt' => new \DateTime("2017-05-20 00:31:47")];
        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->manager->findOneBy($criteria);
        $this->manager->update($utilisateur);
        $utilisateurPostUpdate = $this->manager->findOneBy($criteria);
        $this->assertNotEmpty($utilisateurPostUpdate->getUpdatedAt());
    }

}
