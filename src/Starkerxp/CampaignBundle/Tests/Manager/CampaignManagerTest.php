<?php

namespace Starkerxp\CampaignBundle\Tests\Manager;

use Starkerxp\CampaignBundle\Entity\Campaign;
use Starkerxp\CampaignBundle\Manager\CampaignManager;

class CampaignManagerTest extends \Starkerxp\StructureBundle\Tests\WebTest
{
    /** @var CampaignManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campaign.manager.campaign');
    }

    /**
     * @group campaign
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/CampaignManager.yml',
            ]
        );
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group campaign
     * @group manager
     */
    public function testInsertNewCampaign()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $campaign = new \Starkerxp\CampaignBundle\Entity\Campaign();
        $campaign->setName('Ma super campaign');
        $campaign->setDeleted(false);
        $campaign->setStatus(Campaign::DRAFT);
        $this->manager->insert($campaign);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group campaign
     * @group manager
     */
    public function testUpdateCampaign()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $criteria = ['createdAt' => new \DateTime('2016-08-05 12:12:12')];
        $campaign = $this->manager->findOneBy($criteria);
        $campaign->setStatus(Campaign::ERROR);
        $this->manager->update($campaign);
        $campaignPostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals(Campaign::ERROR, $campaignPostUpdate->getStatus());
        $this->assertNotEmpty($campaignPostUpdate->getUpdatedAt());
    }
}
