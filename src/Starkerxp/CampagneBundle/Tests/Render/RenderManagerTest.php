<?php

namespace Starkerxp\CampagneBundle\Tests\Render;


use Starkerxp\CampagneBundle\Render\RenderManager;

class RenderManagerTest extends \Starkerxp\StructureBundle\Tests\WebTest
{

    /** @var  RenderManager */
    protected $renderService;

    public function setUp()
    {
        parent::setUp();
        $this->renderService = $this->getContainer()->get('starkerxp_campagne.manager.render');


    }

    public function dataProvider()
    {
        $export = [
            'lien mirroir'        => [
                '[{@mirror}]',
                [],
                '',
            ],
            'pixel'               => [
                '[{@pixel}]',
                [],
                '',
            ],
            'lien desinscription' => [
                '<a data-id="unsub" target="__blank" href="http://google.fr" style="color:black;">Mon lien</a>',
                [],
                "Mon lien (http://google.fr)",
            ],
            'lien clickable'      => [
                '<a data-id="click" target="__blank" href="http://google.fr" style="color:black;">Mon lien</a>',
                [],
                "Mon lien (http://google.fr)",
            ],
        ];

        return $export;
    }

    /**
     * @group campagne
     * @group render
     * @group renderManager
     *
     * @dataProvider dataProvider
     */
    public function testRender($message, $params, $expected)
    {
        $this->renderService->setApi("");
        $this->renderService->setVersion("txt");
        $this->renderService->setContenu($message);
        $this->renderService->setData($params);
        $actual = $this->renderService->render();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @group campagne
     * @group render
     * @group renderManager
     *
     * @expectedException \Starkerxp\CampagneBundle\Render\Exception\ApiNotDefinedException
     */
    public function testRenderWithoutApi()
    {
        $this->renderService->setVersion("txt");
        $this->renderService->setContenu("Mon message");
        $this->renderService->setData([]);
        $this->renderService->render();
    }

    /**
     * @group campagne
     * @group render
     * @group renderManager
     *
     * @expectedException \Starkerxp\CampagneBundle\Render\Exception\VersionNotDefinedException
     */
    public function testRenderWithoutVersion()
    {
        $this->renderService->setApi("twig");
        $this->renderService->setContenu("Mon message");
        $this->renderService->setData([]);
        $this->renderService->render();
    }
}
