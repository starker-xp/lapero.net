<?php

namespace Starkerxp\CampaignBundle\Tests\Render;


use Starkerxp\CampaignBundle\Render\HtmlToTxtRender;

class HtmlToTxtRenderTest extends \Starkerxp\StructureBundle\Test\WebTest
{
    /** @var HtmlToTxtRender */
    protected $renderService;

    public function setUp()
    {
        parent::setUp();
        $this->renderService = $this->getContainer()->get('starkerxp_campaign.render.html_to_txt');
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
     * @group campaign
     * @group render
     * @group htmltotxt
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
