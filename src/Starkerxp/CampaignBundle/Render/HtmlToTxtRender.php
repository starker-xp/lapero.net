<?php


namespace Starkerxp\CampaignBundle\Render;

class HtmlToTxtRender extends AbstractRender
{
    /**
     * @var \HTMLPurifier
     */
    private $htmlPurifierService;

    /**
     * HtmlToTxtRender constructor.
     *
     * @param \HTMLPurifier $htmlPurifierService
     */
    public function __construct(\HTMLPurifier $htmlPurifierService)
    {
        $this->htmlPurifierService = $htmlPurifierService;
    }

    public function render()
    {
        $monTexte = preg_replace("#\<[[:space:]]?br[[:space:]]?\/?[[:space:]]?>#", "<br/>\n", $this->getContenu());
        $monTexte = preg_replace("#\<\/[[:space:]]?p[[:space:]]?>#", "</p>\n\n", $monTexte);
        $monTexte = preg_replace("#\<\/[[:space:]]?div[[:space:]]?>#", "</div>\n\n", $monTexte);
        $monTexte = $this->htmlPurifierService->purify(
            $monTexte,
            [
                'AutoFormat.DisplayLinkURI' => true,
            ]
        );
        $monTexte = strip_tags($monTexte);
        $monTexte = implode(
            "\n",
            array_map(
                function ($ligne) {
                    return trim($ligne);
                },
                explode("\n", $monTexte)
            )
        );
        $monTexte = str_replace(["[{@mirror}]", "[{@pixel}]"], "", $monTexte);

        return $monTexte;
    }

    public function getSupport($api, $version)
    {
        return empty($api) && $version == 'txt';
    }
}
