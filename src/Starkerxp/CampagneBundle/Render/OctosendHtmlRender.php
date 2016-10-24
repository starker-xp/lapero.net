<?php

namespace Starkerxp\CampagneBundle\Render;

class OctosendHtmlRender extends AbstractRender
{
    public function render()
    {
        // Gestion des liens mirror
        $contenu = $this->renderMirror($this->contenu);
        // Gestion des liens pixels
        $contenu = $this->renderPixel($contenu);
        // Gestion des liens de desinscriptions.
        $contenu = $this->renderLien("unsub", $contenu);
        // Gestion des liens click:http://
        $contenu = $this->renderLien("click", $contenu);
        $contenu = str_replace('  ', ' ', str_replace('  ', ' ', $contenu));

        return $contenu;
    }

    protected function renderMirror($contenu)
    {
        $contenuReplace = preg_replace('/\[\{\@mirror\}\]/', '{{mirror}}', $contenu);

        return $contenuReplace;
    }

    protected function renderPixel($contenu)
    {
        $contenuReplace = preg_replace('/\[\{\@pixel\}\]/', '{{pixel}}', $contenu);

        return $contenuReplace;
    }

    protected function renderLien($type, $contenu)
    {
        if (!in_array($type, ["unsub", "click"])) {
            throw new \InvalidArgumentException();
        }
        $arrayContenu = [];
        preg_match_all("#<a data-id=\"".$type."\" target=\"__blank\" href=\"(.*?)\" style=\"(.*?)\">(.*?)</a>#", $contenu, $arrayContenu);
        if (empty($arrayContenu[0])) {
            return $contenu;
        }
        foreach ($arrayContenu[0] as $key => $chaineARemplacer) {
            $chaineOctoSend = $this->retournerLaChaine($type, $arrayContenu[1][$key], $arrayContenu[3][$key], $arrayContenu[2][$key]);
            $contenu = str_replace($chaineARemplacer, $chaineOctoSend, $contenu);
        }

        return $contenu;

    }

    protected function retournerLaChaine($type, $lien, $texte, $style = null)
    {
        if ($type == "unsub") {
            return "<a href='{{unsubscribe:".$lien."}}' style='".$style."' title='Désinscription'>".$texte.'</a>';
        }

        return "<a href='{{click:".$lien."}}' style='".$style."'>".$texte.'</a>'; ;
    }

    public function getSupport($api, $version)
    {
        return strtolower($api) == 'octosend' && $version == 'html';
    }


}
