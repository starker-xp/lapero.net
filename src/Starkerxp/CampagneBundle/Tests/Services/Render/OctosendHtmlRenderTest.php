<?php

namespace Starkerxp\CampagneBundle\Tests\Services\Render;

use Starkerxp\CampagneBundle\Services\Render\OctosendHtmlRender;

class OctosendHtmlRenderTest extends \Starkerxp\StructureBundle\Tests\WebTest
{

    /** @var OctosendHtmlRender */
    protected $renderService;

    public function setUp()
    {
        parent::setUp();
        $this->renderService = $this->getContainer()->get('starkerxp_campagne.render.octosend_html');
    }

    public function dataProvider()
    {
        $export = [
            "lien mirroir"        => ["[{@mirror}]", "{{mirror}}"],
            "pixel"               => ["[{@pixel}]", "{{pixel}}"],
            "lien desinscription" => [
                "<a data-id=\"unsub\" target=\"__blank\" href=\"http://google.fr\" style=\"color:black;\">Mon lien</a>",
                "<a href='{{unsubscribe:http://google.fr}}' style='color:black;' title='Désinscription'>Mon lien</a>",
            ],
            "lien clickable"      => [
                "<a data-id=\"click\" target=\"__blank\" href=\"http://google.fr\" style=\"color:black;\">Mon lien</a>",
                "<a href='{{click:http://google.fr}}' style='color:black;'>Mon lien</a>",
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
        $actual = $this->renderService->render("octosend", "html");
        $this->assertEquals($expected, $actual);
    }

}
