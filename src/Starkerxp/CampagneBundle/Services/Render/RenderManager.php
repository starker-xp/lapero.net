<?php

namespace Starkerxp\CampagneBundle\Services\Render;


use Starkerxp\CampagneBundle\Services\Render\Exception\RenderNotExistException;

class RenderManager extends AbstractRender
{

    /**
     * @var array
     */
    private $renderService = [];

    /**
     * @return mixed
     */
    public function render($api, $version)
    {

        $contenu = $this->getContenu();

        if ($renderServiceTwig = $this->getRender("twig", $version)) {
            $renderServiceTwig->setData($this->getData());
            $renderServiceTwig->setContenu($contenu);
            $contenu = $renderServiceTwig->render("twig", $version);
            if (strtolower($api) == "twig") {
                return $contenu;
            }
        }
        if ($renderService = $this->getRender($api, $version)) {
            $renderService->setData($this->getData());
            $renderService->setContenu($contenu);

            return $renderService->render($api, $version);
        }
    }

    public function getRender($api, $version)
    {
        foreach ($this->renderService as $service) {
            if ($service instanceof RenderInterface && $service->getRender($api, $version)) {
                return $service;
            }
        }
        throw new RenderNotExistException();
    }

    /**
     * @param RenderInterface $service
     *
     * @return $this
     */
    public function addRenderService(RenderInterface $service)
    {
        $this->renderService[] = $service;

        return $this;
    }

    /**
     * @return array
     */
    public function getRenderService()
    {
        return $this->renderService;
    }


}