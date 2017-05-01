<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 02/05/2017
 * Time: 00:20
 */

namespace Starkerxp\CampagneBundle\Api;


interface ApiInterface
{
    public function getSupport();

    public function setConfig(array $config);

    public function setDestinataire($destinataire);

    public function envoyer(array $content, array $options);

    public function getRetour();
}
