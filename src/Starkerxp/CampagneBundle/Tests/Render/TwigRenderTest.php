<?php

namespace Starkerxp\CampagneBundle\Tests\Render;

use Starkerxp\CampagneBundle\Render\TwigRender;

class TwigRenderTest extends \Starkerxp\StructureBundle\Tests\WebTest
{
    /** @var TwigRender */
    protected $renderService;

    public function setUp()
    {
        parent::setUp();
        $this->renderService = $this->getContainer()->get('starkerxp_campagne.render.twig');
    }

    public function dataProvider()
    {
        return [
            ['Bonjour {{prenom|capitalize}} {{nom |upper}}.', ['nom' => 'CAOUIsSIN', 'prenom' => 'guillaume'], 'Bonjour Guillaume CAOUISSIN.'],
        ];
    }

    /**
     * @group campagne
     * @group render
     * @group twig
     *
     * @dataProvider dataProvider
     */
    public function testRender($message, $params, $expected)
    {
        $this->renderService->setContenu($message);
        $this->renderService->setData($params);
        $actual = $this->renderService->render();
        $this->assertEquals($expected, $actual);
    }
}
