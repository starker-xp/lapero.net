<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 02/05/2017
 * Time: 00:21
 */

namespace Starkerxp\CampaignBundle\Api;

abstract class AbstractApi implements ApiInterface
{
    protected $config;
    protected $api;
    protected $retour;

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getRetour()
    {
        return $this->retour;
    }
}
