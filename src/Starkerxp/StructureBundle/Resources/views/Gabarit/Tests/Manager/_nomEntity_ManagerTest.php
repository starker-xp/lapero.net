<?php

namespace {{ namespace }}\Tests\Manager;

use {{ namespace }}\Manager\{{ nomEntity }}Manager;

class {{nomEntity}}ManagerTest extends \Starkerxp\StructureBundle\Tests\WebTest
{

    /** @var  {{ nomEntity }}Manager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->getContainer()->get('{{nomService}}.manager.{{ nomEntity|lower }}');
    }

    /**
     * @group {{ nomEntity |lower}}
     * @group manager
     */
    public function testFindAll()
    {
        $this->loadFixtureFiles(['{{namespaceBundle}}/Tests/DataFixtures/{{ nomEntity }}Manager/{{ nomEntity }}Manager.yml',]);
        $this->assertCount(10, $this->manager->findAll());
    }

    /**
     * @group {{ nomEntity |lower}}
     * @group manager
     */
    public function testInsertNew{{ nomEntity }}()
    {
		$this->loadFixtureFiles(['{{namespaceBundle}}/Tests/DataFixtures/{{ nomEntity }}Manager/Default{{ nomEntity }}.yml',]);
        ${{ nomEntity|lower }} = new \{{namespace}}\Entity\{{ nomEntity }}();
        $this->manager->insert(${{ nomEntity|lower }});
        $this->assertCount(2, $this->manager->findAll());
    }

    /**
     * @group {{ nomEntity |lower}}
     * @group manager
     */
    public function testUpdate{{ nomEntity }}()
    {
        $this->loadFixtureFiles(['{{namespaceBundle}}/Tests/DataFixtures/{{ nomEntity }}Manager/Default{{ nomEntity }}.yml',]);
		$criteria = ['createdAt' => new \DateTime("2016-08-05 12:12:12")];
        ${{ nomEntity|lower }} = $this->manager->findOneBy($criteria);
       
        $this->manager->update(${{ nomEntity|lower }});
        ${{ nomEntity|lower }}PostUpdate = $this->manager->findOneBy($criteria);
        $this->assertEquals(1, ${{ nomEntity|lower }}PostUpdate->getIsError());
        $this->assertNotEmpty(${{ nomEntity|lower }}PostUpdate->getUpdatedAt());
    }

}
