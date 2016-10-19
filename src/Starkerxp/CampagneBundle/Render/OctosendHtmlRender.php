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

    protected function renderLien($key, $contenu)
    {
        if (!in_array($key, ["unsub", "click"])) {
            throw new \InvalidArgumentException();
        }
        $arrayContenu = [];
        preg_match_all("#<a data-id=\"".$key."\" target=\"__blank\" href=\"(.*?)\" style=\"(.*?)\">(.*?)</a>#", $contenu, $arrayContenu);
        if (empty($arrayContenu[0])) {
            return $contenu;
        }
        if ($key == "unsub") {
            return $this->renderDesinscription($contenu, $arrayContenu);
        }

        return $this->renderClick($contenu, $arrayContenu);

    }

    protected function renderDesinscription($contenu, $arrayContenu)
    {
        foreach ($arrayContenu[0] as $key => $chaineARemplacer) {
            $chaineOctoSend = "<a href='{{unsubscribe:".$arrayContenu[1][$key]."}}' style='".$arrayContenu[2][$key]."' title='Désinscription'>".$arrayContenu[3][$key].'</a>';
            $contenu = str_replace($chaineARemplacer, $chaineOctoSend, $contenu);
        }

        return $contenu;
    }

    protected function renderClick($contenu, $arrayContenu)
    {
        foreach ($arrayContenu[0] as $key => $chaineARemplacer) {
            $chaineOctoSend = "<a href='{{click:".$arrayContenu[1][$key]."}}' style='".$arrayContenu[2][$key]."'>".$arrayContenu[3][$key].'</a>';
            $contenu = str_replace($chaineARemplacer, $chaineOctoSend, $contenu);
        }

        return $contenu;
    }

    public function getSupport($api, $version)
    {
        return strtolower($api) == 'octosend' && $version == 'html';
    }

}
