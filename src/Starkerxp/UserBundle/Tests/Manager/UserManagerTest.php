<?php

namespace Starkerxp\UserBundle\Tests\Manager;

use Starkerxp\StructureBundle\Test\WebTest;
use Starkerxp\UserBundle\Entity\User;
use Starkerxp\UserBundle\Manager\UserManager;

class UserManagerTest extends WebTest
{

    /** @var  UserManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_user.manager.user');
    }

    /**
     * @group user
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(['@StarkerxpUserBundle/Tests/DataFixtures/UserManager/UserManager.yml',]);
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group user
     * @group manager
     */
    public function testInsertNewUser()
    {
        $this->loadFixtureFiles(['@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',]);
        $user = new User('test2@yopmail.com', ["ROLE_SUPER_ADMIN"]);
        $user->setPlainPassword("azerty");
        $this->manager->insert($user);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group user
     * @group manager
     */
    public function testUpdateUser()
    {
        $this->loadFixtureFiles(['@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',]);
        $criteria = ['createdAt' => new \DateTime("2017-05-20 00:31:47")];
        /** @var User $user */
        $user = $this->manager->findOneBy($criteria);
        $this->manager->update($user);
        $userPostUpdate = $this->manager->findOneBy($criteria);
        $this->assertNotEmpty($userPostUpdate->getUpdatedAt());
    }

}
