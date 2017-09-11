<?php

namespace Starkerxp\CampaignBundle\Tests\Render;

use Starkerxp\CampaignBundle\Render\OctosendTxtRender;

class OctosendTxtRenderTest extends \Starkerxp\StructureBundle\Test\WebTest
{
    /** @var OctosendTxtRender */
    protected $renderService;

    public function setUp()
    {
        parent::setUp();
        $this->renderService = $this->getContainer()->get('starkerxp_campaign.render.octosend_txt');
    }

    public function dataProvider()
    {
        $export = [
            'lien mirroir' => ['[{@mirror}]', [], '{{mirror}}'],
            'pixel' => ['[{@pixel}]', [], '{{pixel}}'],
            'lien desinscription' => [
                '<a data-id="unsub" target="__blank" href="http://google.fr" style="color:black;">Mon lien</a>',
                [],
                '[Désinscription] {{unsubscribe:http://google.fr}}',
            ],
            'lien clickable' => [
                '<a data-id="click" target="__blank" href="http://google.fr" style="color:black;">Mon lien</a>',
                [],
                '[Mon lien] {{click:http://google.fr}}',
            ],
        ];

        return $export;
    }

    /**
     * @group campaign
     * @group render
     * @group octosend
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
