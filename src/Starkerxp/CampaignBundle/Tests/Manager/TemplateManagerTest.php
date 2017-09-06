<?php

namespace Starkerxp\CampaignBundle\Tests\Manager;

use Starkerxp\CampaignBundle\Entity\Template;
use Starkerxp\CampaignBundle\Manager\TemplateManager;

class TemplateManagerTest extends \Starkerxp\StructureBundle\Tests\WebTest
{
    /** @var TemplateManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campaign.manager.template');
    }

    /**
     * @group template
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
            ]
        );
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group template
     * @group manager
     */
    public function testInsertNewCampaign()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml',
            ]
        );
        $template = new Template();
        $template->setType("email");
        $template->setName("Ceci est mon nom");
        $template->setObject("Ceci est mon sujet");
        $template->setMessage("Ceci est mon message");
        $template->setUuid("5e6e63e6-1d74-4c4c-b19a-2741ed330837");
        $this->manager->insert($template);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group template
     * @group manager
     */
    public function testUpdateCampaign()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml',
            ]
        );
        $template = $this->manager->findOneBy(['uuid' => "5e6e63e6-1d74-4c4c-b19a-2741ed330836"]);
        $type = $template->getType();
        $template->setType("sms");
        $this->manager->update($template);
        $this->manager->clear();
        $template = $this->manager->findOneBy(['uuid' => "5e6e63e6-1d74-4c4c-b19a-2741ed330836"]);
        $this->assertNotEquals($type, $template->getType());
    }
}
