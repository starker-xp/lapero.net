<?php

namespace Starkerxp\CampaignBundle\Render;

interface RenderInterface
{
    public function setContenu($contenu);

    public function getContenu();

    public function setData(array $data);

    public function getData();

    public function render();

    public function getSupport($api, $version);
}
