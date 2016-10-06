<?php

namespace Starkerxp\CampagneBundle\Render;

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
        $content = $this->getContenu();

        if ($renderServiceTwig = $this->getRender('twig', $version)) {
            $renderServiceTwig->setData($this->getData());
            $renderServiceTwig->setContenu($content);
            $content = $renderServiceTwig->render('twig', $version);
            if (strtolower($api) == 'twig') {
                return $content;
            }
        }
        if ($renderService = $this->getRender($api, $version)) {
            $renderService->setData($this->getData());
            $renderService->setContenu($content);

            return $renderService->render($api, $version);
        }

        return $content;
    }

    public function getSupport($api, $version)
    {
        foreach ($this->renderService as $service) {
            if ($service instanceof RenderInterface && $service->getRender($api, $version)) {
                return $service;
            }
        }
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
