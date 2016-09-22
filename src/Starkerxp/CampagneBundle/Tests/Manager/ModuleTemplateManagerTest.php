<?php

namespace Starkerxp\CampagneBundle\Tests\Manager;

use Starkerxp\CampagneBundle\Manager\ModuleTemplateManager;

class ModuleTemplateManagerTest extends \Starkerxp\StructureBundle\Tests\WebTest
{

    /** @var  ModuleTemplateManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('starkerxp_campagne.manager.module_template');
    }

    /**
     * @group campagne
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/ModuleTemplateManager/ModuleTemplateManager.yml',]);
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group campagne
     * @group manager
     */
    public function testInsertNewCampagne()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/ModuleTemplateManager/DefaultModuleTemplate.yml',]);
        $moduleTemplate = new \Starkerxp\CampagneBundle\Entity\ModuleTemplate();
        $moduleTemplate->setCode("[@monCode]");
        $moduleTemplate->setContenuHtml("<strong>{%if prenom is not empty%}Bonjour {{prenom|capitalize}}{%endif%}</strong>");
        $moduleTemplate->setContenuTxt("{%if prenom is not empty%}Bonjour {{prenom|capitalize}}{%endif%}");
        $this->manager->insert($moduleTemplate);
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group campagne
     * @group manager
     */
    public function testUpdateCampagne()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/ModuleTemplateManager/DefaultModuleTemplate.yml',]);
        $criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        $campagne = $this->manager->findOneBy($criteria);
        $campagne->setCode("[@monCode2]");
        $this->manager->update($campagne);
        $campagnePostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals("[@monCode2]", $campagnePostUpdate->getCode());
        $this->assertNotEmpty($campagnePostUpdate->getUpdatedAt());
    }

}
