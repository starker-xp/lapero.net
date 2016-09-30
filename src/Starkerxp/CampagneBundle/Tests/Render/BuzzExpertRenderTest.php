<?php

namespace Starkerxp\CampagneBundle\Tests\Render;

use Starkerxp\CampagneBundle\Render\BuzzExpertRender;

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
            ["Bonjouré ï {{prenom|capitalize}} {{nom |upper}}.", ["nom" => "CAOUIsSIN", "prenom" => "guillaume"], "Bonjouré i Guillaume CAOUISSIN."],
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
        $actual = $this->renderService->render("buzzexpert", "txt");
        $this->assertEquals($expected, $actual);
    }

}
