<?php

namespace Starkerxp\CampaignBundle\Tests\Manager;

use Starkerxp\CampaignBundle\Entity\CampaignTarget;
use Starkerxp\CampaignBundle\Manager\CampaignTargetManager;
use Starkerxp\StructureBundle\Test\WebTest;

class CampaignTargetManagerTest extends WebTest
{

    /** @var  CampaignTargetManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campaign.manager.cible');
    }

    /**
     * @group cible
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/CampaignManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignTargetManager/CampaignTargetManager.yml',
            ]
        );
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group cible
     * @group manager
     */
    public function testInsertNewCampaignTarget()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/CampaignManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignTargetManager/DefaultCampaignTarget.yml',
            ]
        );
        $campaigns = $this->getRepository("StarkerxpCampaignBundle:Campaign")->findBy([], ['id' => 'ASC']);
        $cible = new CampaignTarget();
        $cible->setCampaign($campaigns[1]);
        $this->manager->insert($cible);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group cible
     * @group manager
     */
    public function testUpdateCampaignTarget()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/CampaignManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignTargetManager/DefaultCampaignTarget.yml',
            ]
        );
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $cible = $this->manager->findOneBy($criteria);
        $this->manager->update($cible);
        $ciblePostUpdate = $this->manager->findOneBy($criteria);
        $this->assertNotEmpty($ciblePostUpdate->getUpdatedAt());
    }

}
