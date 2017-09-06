<?php

namespace Starkerxp\CampaignBundle\Render;

use Starkerxp\CampaignBundle\Render\Exception\ApiNotDefinedException;
use Starkerxp\CampaignBundle\Render\Exception\VersionNotDefinedException;

class RenderManager extends AbstractRender
{
    /**
     * @var array
     */
    private $renderService = [];

    /**
     * @var string
     */
    private $api;

    /**
     * @var string
     */
    private $version;

    /**
     * @throws ApiNotDefinedException
     * @throws VersionNotDefinedException
     *
     * @return string
     */
    public function render()
    {
        if (!isset($this->api)) {
            throw new ApiNotDefinedException();
        }
        if (!isset($this->version)) {
            throw new VersionNotDefinedException();
        }
        $content = $this->getContenu();
        $listesApi = array_unique(array_merge([], !is_array($this->api) ? [$this->api] : $this->api));
        foreach ($listesApi as $api) {
            if ($renderService = $this->getSupport($api, $this->version)) {
                $renderService->setData($this->getData());
                $renderService->setContenu($content);
                $content = $renderService->render();
            }
        }

        return $content;
    }

    /**
     * @param string $api
     * @param string $version
     */
    public function getSupport($api, $version)
    {
        foreach ($this->renderService as $service) {
            if ($service instanceof RenderInterface && $service->getSupport($api, $version)) {
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

    /**
     * @return string
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param string $api
     *
     * @return RenderManager
     */
    public function setApi($api)
    {
        $this->api = strtolower($api);

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     *
     * @return RenderManager
     */
    public function setVersion($version)
    {
        $this->version = strtolower($version);

        return $this;
    }
}
