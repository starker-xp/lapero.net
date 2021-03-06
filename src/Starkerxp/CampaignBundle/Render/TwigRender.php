<?php

namespace Starkerxp\CampaignBundle\Render;

use Twig_Environment;

class TwigRender extends AbstractRender
{
    /** @var Twig_Environment */
    protected $service;

    public function __construct(Twig_Environment $twigService)
    {
        $this->service = $twigService;
        $this->service->disableStrictVariables();
    }

    public function render()
    {
        $twigRender = $this->service->createTemplate($this->contenu);
        $renderedTmp = $twigRender->render($this->data);
        $rendered = str_replace('  ', ' ', str_replace('  ', ' ', str_replace(' , ', ', ', $renderedTmp)));

        return $rendered;
    }

    public function getSupport($api, $version)
    {
        return $api == 'twig';
    }
}
