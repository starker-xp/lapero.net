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

    protected function retournerLaChaine($type, $lien, $texte, $style = null)
    {
        if ($type == "unsub") {
            return "<a href='{{unsubscribe:".$lien."}}' style='".$style."' title='Désinscription'>".$texte.'</a>';
        }

        return (!empty($texte) && $texte != 'Lien' ? '['.$texte.']' : '').' {{click:'.$lien.'}}';
    }


}
