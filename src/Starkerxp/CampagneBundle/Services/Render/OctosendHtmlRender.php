<?php

namespace Starkerxp\CampagneBundle\Services\Render;

class OctosendHtmlRender extends AbstractRender
{

    public function render()
    {
        // Gestion des liens mirror
        $contenu = $this->renderMirror($this->contenu);
        // Gestion des liens pixels
        $contenu = $this->renderPixel($contenu);
        // Gestion des liens de desinscriptions.
        $contenu = $this->renderDesinscription($contenu);
        // Gestion des liens click:http://
        $contenu = $this->renderClick($contenu);
        $contenu = str_replace("  ", " ", str_replace("  ", " ", $contenu));
        return $contenu;
    }

    protected function renderMirror($contenu)
    {
        $contenuReplace = preg_replace('/\[\{\@mirror\}\]/', "{{mirror}}", $contenu);
        return $contenuReplace;
    }

    protected function renderPixel($contenu)
    {
        $contenuReplace = preg_replace('/\[\{\@pixel\}\]/', "{{pixel}}", $contenu);
        return $contenuReplace;
    }

    protected function renderDesinscription($contenu)
    {
        $arrayContenu = array();
        preg_match_all("#\<a data\-id\=\"unsub\" target=\"__blank\" href\=\"(.*?)\" style\=\"(.*?)\"\>(.*?)\<\/a\>#", $contenu, $arrayContenu);
        if (empty($arrayContenu[0])) {
            return $contenu;
        }
        foreach ($arrayContenu[0] as $key => $chaineARemplacer) {
            $chaineOctoSend = "<a href='{{unsubscribe:" . $arrayContenu[1][$key] . "}}' style='" . $arrayContenu[2][$key] . "' title='Désinscription'>" . $arrayContenu[3][$key] . "</a>";
            $contenu = str_replace($chaineARemplacer, $chaineOctoSend, $contenu);
        }
        return $contenu;
    }

    protected function renderClick($contenu)
    {
        $arrayContenu = array();
        preg_match_all("#\<a data\-id\=\"click\" target=\"__blank\" href\=\"(.*?)\"\ style\=\"(.*?)\"\>(.*?)\<\/a\>#", $contenu, $arrayContenu);
        if (empty($arrayContenu[0])) {
            return $contenu;
        }
        foreach ($arrayContenu[0] as $key => $chaineARemplacer) {
            $chaineOctoSend = "<a href='{{click:" . $arrayContenu[1][$key] . "}}' style='" . $arrayContenu[2][$key] . "'>" . $arrayContenu[3][$key] . "</a>";
            $contenu = str_replace($chaineARemplacer, $chaineOctoSend, $contenu);
        }
        return $contenu;
    }

}
