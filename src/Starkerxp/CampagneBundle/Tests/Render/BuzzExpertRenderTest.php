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
            ['Bonjouré ï {{prenom|capitalize}} {{nom |upper}}.', ['nom' => 'CAOUIsSIN', 'prenom' => 'guillaume'], 'Bonjouré i {{prenom|capitalize}} {{nom |upper}}.'],
            ['Ceci est un texte valide. Et validé', [], 'Ceci est un texte valide. Et validé'],
            ['CÂ va être€ convertie!ç', [], 'CA va etreE convertie!c'],
            ['ÂâÁáÃãᾹᾱÇçČčĆćÊêËëĖėïÎîÍíńÔôÓóÕõŒœŌōŚśŠšÛûŪūӰӱ€', [], 'AaAaAaAaCcCcCcEeEeEeiIiIinOoOoOooeoeOoSsSsUuUuYyE'],
            ['$', [], 'USD'],
            ['£', [], 'GBP'],
            ['‘`', [], ''],
            ['"', [], ' '],
        ];
    }

    /**
     * @group campagne
     * @group render
     * @group buzzexpert
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
