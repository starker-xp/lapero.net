<?php

namespace Starkerxp\CampagneBundle\Tests\Render;

use Starkerxp\CampagneBundle\Render\OctosendTxtRender;

class OctosendTxtRenderTest extends \Starkerxp\StructureBundle\Tests\WebTest
{
    /** @var OctosendTxtRender */
    protected $renderService;

    public function setUp()
    {
        parent::setUp();
        $this->renderService = $this->getContainer()->get('starkerxp_campagne.render.octosend_txt');
    }

    public function dataProvider()
    {
        $export = [
            'lien mirroir' => ['[{@mirror}]', '{{mirror}}'],
            'pixel' => ['[{@pixel}]', '{{pixel}}'],
            'lien desinscription' => [
                '<a data-id="unsub" target="__blank" href="http://google.fr" style="color:black;">Mon lien</a>',
                '[Mon lien] {{unsubscribe:http://google.fr}}',
            ],
            'lien clickable' => [
                '<a data-id="click" target="__blank" href="http://google.fr" style="color:black;">Mon lien</a>',
                '[Mon lien] {{click:http://google.fr}}',
            ],
        ];

        return $export;
    }

    /**
     * @group campagne
     * @group render
     * @group octosend
     *
     * @dataProvider dataProvider
     */
    public function testRender($message, $expected)
    {
        $this->renderService->setContenu($message);
        $actual = $this->renderService->render('octosend', 'txt');
        $this->assertEquals($expected, $actual);
    }
}
