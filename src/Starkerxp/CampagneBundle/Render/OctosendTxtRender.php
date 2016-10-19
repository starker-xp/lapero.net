<?php

namespace Starkerxp\CampagneBundle\Render;

class OctosendTxtRender extends OctosendHtmlRender
{
    /**
     * @var AbstractRender
     */
    private $htmlToTxtService;

    /**
     * OctosendTxtRender constructor.
     *
     * @param AbstractRender $htmlToTxtService
     */
    public function __construct(AbstractRender $htmlToTxtService)
    {
        $this->htmlToTxtService = $htmlToTxtService;
    }

    public function render()
    {
        // Gestion des liens de desinscriptions.
        $contenu = $this->renderLien("unsub", $this->contenu);
        // Gestion des liens click:http://
        $contenu = $this->renderLien("click", $contenu);

        $this->htmlToTxtService->setContenu($contenu);
        $contenu = $this->htmlToTxtService->render();

        // Gestion des liens mirror
        $contenu = $this->renderMirror($contenu);
        // Gestion des liens pixels
        $contenu = $this->renderPixel($contenu);

        $contenu = str_replace('  ', ' ', str_replace('  ', ' ', $contenu));

        return $contenu;
    }

    public function getSupport($api, $version)
    {
        return strtolower($api) == 'octosend' && $version == 'txt';
    }

    protected function renderClick($contenu, $arrayContenu)
    {
        foreach ($arrayContenu[0] as $key => $chaineARemplacer) {
            $chaineOctoSend = (!empty($arrayContenu[3][$key]) && $arrayContenu[3][$key] != 'Lien' ? '['.$arrayContenu[3][$key].']' : '').' {{click:'.$arrayContenu[1][$key].'}}';
            $contenu = str_replace($chaineARemplacer, $chaineOctoSend, $contenu);
        }

        return $contenu;
    }
}
