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

        $contenu = parent::render();

        // On finit la conversion en convertissant le reste des élements html en txt
        $this->htmlToTxtService->setContenu($contenu);
        $contenu = $this->htmlToTxtService->render();

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
