<?php

namespace Starkerxp\CampagneBundle\Tests\Services\Render;

use Starkerxp\CampagneBundle\Services\Render\BuzzExpertRender;

class BuzzExpertRenderTest extends \Starkerxp\StructureBundle\Tests\WebTest
{

    /** @var BuzzExpertRender */
    protected $renderService;

    public function setUp()
    {
        parent::setUp();
        $this->renderService = $this->getContainer()->get('starkerxp_campagne.render.buzzexpert');
    }

    public function dataProvider()
    {
        return [
                ["Ceci est un texte valide. Et validé", "Ceci est un texte valide. Et validé"],
                ["CÂ va être€ convertie!ç", "CA va etreE convertie!c"],
                ["ÂâÁáÃãᾹᾱÇçČčĆćÊêËëĖėïÎîÍíńÔôÓóÕõŒœŌōŚśŠšÛûŪūӰӱ€", "AaAaAaAaCcCcCcEeEeEeiIiIinOoOoOooeoeOoSsSsUuUuYyE"],
                ['$', "USD"],
                ['£', "GBP"],
                ['‘`', ""],
                ['"', ' '],
        ];
    }

    /**
     * @group campagne
     * @group render
     * @group buzzexpert
     *
     * @dataProvider dataProvider
     */
    public function testRender($message, $expected)
    {
        $this->renderService->setContenu($message);
        $actual = $this->renderService->render();
        $this->assertEquals($expected, $actual);
    }

}
