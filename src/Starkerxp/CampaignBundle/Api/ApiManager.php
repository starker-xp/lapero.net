<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 02/05/2017
 * Time: 00:20
 */

namespace Starkerxp\CampaignBundle\Api;

class ApiManager extends AbstractApi
{
    /**
     * @var array
     */
    private $apiService = [];

    /**
     * @param $destinataire
     * @param array $content
     * @param array $options
     * @return bool
     */
    public function envoyer($destinataire, array $content, array $options = [])
    {
        if (empty($content)) {
            return false;
        }
        if ($apiService = $this->getSupport()) {
            $apiService->envoyer($destinataire, $content, $options);
        }
        return true;
    }
    /**
     * @return bool|mixed
     */
    public function getSupport()
    {
        if (empty($this->config)) {
            return false;
        }

        foreach ($this->apiService as $service) {
            $service->setConfig($this->config);
            if ($service instanceof ApiInterface && $service->getSupport()) {
                return $service;
            }
        }
        return false;
    }
    /**
     * @param ApiInterface $service
     *
     * @return $this
     */
    public function addApiService(ApiInterface $service)
    {
        $this->apiService[] = $service;
        return $this;
    }
    /**
     * @return array
     */
    public function getApiService()
    {
        return $this->apiService;
    }

}
