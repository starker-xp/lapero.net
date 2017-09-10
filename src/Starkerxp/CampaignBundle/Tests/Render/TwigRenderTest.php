<?php

namespace Starkerxp\CampaignBundle\Tests\Render;

use Starkerxp\CampaignBundle\Render\TwigRender;

class TwigRenderTest extends \Starkerxp\StructureBundle\Test\WebTest
{
    /** @var TwigRender */
    protected $renderService;

    public function setUp()
    {
        parent::setUp();
        $this->renderService = $this->getContainer()->get('starkerxp_campaign.render.twig');
    }

    public function dataProvider()
    {
        return [
            ['Bonjour {{prenom|capitalize}} {{nom |upper}}.', ['nom' => 'CAOUIsSIN', 'prenom' => 'guillaume'], 'Bonjour Guillaume CAOUISSIN.'],
        ];
    }

    /**
     * @group campaign
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
