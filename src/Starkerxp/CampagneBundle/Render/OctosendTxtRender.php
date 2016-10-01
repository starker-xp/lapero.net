<?php

namespace Starkerxp\CampagneBundle\Render;

class OctosendTxtRender extends OctosendHtmlRender
{
    protected function renderDesinscription($contenu)
    {
        $arrayContenu = array();
        preg_match_all("#\<a data\-id\=\"unsub\" target=\"__blank\" href\=\"(.*?)\" style\=\"(.*?)\"\>(.*?)\<\/a\>#", $contenu, $arrayContenu);
        if (empty($arrayContenu[0])) {
            return $contenu;
        }
        foreach ($arrayContenu[0] as $key => $chaineARemplacer) {
            $chaineOctoSend = '['.$arrayContenu[3][$key].'] {{unsubscribe'.(!empty($arrayContenu[1][$key]) ? ':'.$arrayContenu[1][$key] : '').'}}';
            $contenu = str_replace($chaineARemplacer, $chaineOctoSend, $contenu);
        }

        return $contenu;
    }

    protected function renderClick($contenu)
    {
        $arrayContenu = array();
        //preg_match_all("#\[(.*?)\] ((?:http|https):(?:.*?))([. :!?;])#", $contenu, $arrayContenu);
        preg_match_all("#\<a data\-id\=\"click\" target=\"__blank\" href\=\"(.*?)\"\ style\=\"(.*?)\"\>(.*?)\<\/a\>#", $contenu, $arrayContenu);
        if (empty($arrayContenu[0])) {
            return $contenu;
        }
        foreach ($arrayContenu[0] as $key => $chaineARemplacer) {
            $chaineOctoSend = (!empty($arrayContenu[3][$key]) && $arrayContenu[3][$key] != 'Lien' ? '['.$arrayContenu[3][$key].']' : '').' {{click:'.$arrayContenu[1][$key].'}}';
            $contenu = str_replace($chaineARemplacer, $chaineOctoSend, $contenu);
        }

        return $contenu;
    }

    public function getRender($api, $version)
    {
        return strtolower($api) == 'octosend' && $version == 'txt';
    }
}
