<?php

namespace Starkerxp\CampagneBundle\Tests\Manager;

use Starkerxp\CampagneBundle\Entity\Template;
use Starkerxp\CampagneBundle\Manager\TemplateManager;

class TemplateManagerTest extends \Starkerxp\StructureBundle\Tests\WebTest
{
    /** @var TemplateManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campagne.manager.template');
    }

    /**
     * @group template
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml']);
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group template
     * @group manager
     */
    public function testInsertNewCampagne()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml']);
        $template = new Template();
        $template->setType("email");
        $template->setNom("Ceci est mon nom");
        $template->setSujet("Ceci est mon sujet");
        $template->setMessage("Ceci est mon message");
        $template->setUuid("5e6e63e6-1d74-4c4c-b19a-2741ed330836");
        $this->manager->insert($template);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group template
     * @group manager
     */
    public function testUpdateCampagne()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml']);
        $template = $this->manager->findOneBy(['uuid'=>"5e6e63e6-1d74-4c4c-b19a-2741ed330836"]);
        $type = $template->getType();
        $template->setType("sms");
        $this->manager->update($template);
        $this->manager->clear();
        $template = $this->manager->findOneBy(['uuid'=>"5e6e63e6-1d74-4c4c-b19a-2741ed330836"]);
        $this->assertNotEquals($type, $template->getType());
    }
}
