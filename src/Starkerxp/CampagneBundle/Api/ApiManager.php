<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 02/05/2017
 * Time: 00:20
 */

namespace Starkerxp\CampagneBundle\Api;

class ApiManager extends AbstractApi
{
    /**
     * @var array
     */
    private $apiService = [];
    /**
     * @param array $content
     * @return bool
     */
    public function envoyer(array $content, array $options = [])
    {
        if (empty($content)) {
            return false;
        }
        if ($apiService = $this->getSupport()) {
            $apiService->envoyer($content, $options);
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
        if (empty($this->destinataire)) {
            return false;
        }
        foreach ($this->apiService as $service) {
            $service->setDestinataire($this->destinataire);
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
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;
    }
}
