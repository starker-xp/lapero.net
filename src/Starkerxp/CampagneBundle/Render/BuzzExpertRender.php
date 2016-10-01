<?php

namespace Starkerxp\CampagneBundle\Render;

class BuzzExpertRender extends AbstractRender
{
    public function render($api, $version)
    {
        $contenu = $this->remplacerLesCaracteresSpeciaux(mb_convert_encoding($this->contenu, "UTF-8"));

        return $contenu;
    }

    public function remplacerLesCaracteresSpeciaux($contenu)
    {
        // Gestion des accents.
        $findAccent = array(
            'Â',
            'â',
            'Á',
            'á',
            'Ã',
            'ã',
            'Ᾱ',
            'ᾱ',
            'Ç',
            'ç',
            'Č',
            'č',
            'Ć',
            'ć',
            'Ê',
            'ê',
            'Ë',
            'ë',
            'Ė',
            'ė',
            'ï',
            'Î',
            'î',
            'Í',
            'í',
            'ń',
            'Ô',
            'ô',
            'Ó',
            'ó',
            'Õ',
            'õ',
            'Œ',
            'œ',
            'Ō',
            'ō',
            'Ś',
            'ś',
            'Š',
            'š',
            'Û',
            'û',
            'Ū',
            'ū',
            'Ӱ',
            'ӱ',
        );
        $replaceAccent = array(
            'A',
            'a',
            'A',
            'a',
            'A',
            'a',
            'A',
            'a',
            'C',
            'c',
            'C',
            'c',
            'C',
            'c',
            'E',
            'e',
            'E',
            'e',
            'E',
            'e',
            'i',
            'I',
            'i',
            'I',
            'i',
            'n',
            'O',
            'o',
            'O',
            'o',
            'O',
            'o',
            'oe',
            'oe',
            'O',
            'o',
            'S',
            's',
            'S',
            's',
            'U',
            'u',
            'U',
            'u',
            'Y',
            'y',
        );
        $contenu = str_replace($findAccent, $replaceAccent, $contenu);
        // Gestion des autres caractères.
        $find = array("€", "‘", "$", "£", "`", '"', "#");
        $replace = array("E", "", "USD", "GBP", "", ' ', "");
        $contenu = str_replace($find, $replace, $contenu);
        $contenu = str_replace(["  ", "   "], " ", $contenu);
        $out = [];
        preg_match_all("#[^0-9a-zA-Zéèà\,\!\?\'\(\)\_\%\/\+\=\:\.\-\@\;\<\>\*\ ]#u", $contenu, $out); // Suppresion du caractère \&
        $outTmp = array_filter($out[0]);
        if (empty($outTmp)) {
            return $contenu;
        }
        foreach ($outTmp as $element) {
            $elementVide = empty($element) ? $element : iconv('UTF-8', 'ASCII//TRANSLIT', $element);
            $contenu = str_replace($element, $elementVide, $contenu);
        }

        return $contenu;
    }

    public function getRender($api, $version)
    {
        return strtolower($api) == "buzzexpert";
    }
}
