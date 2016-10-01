<?php

namespace Starkerxp\CampagneBundle\Render;

interface RenderInterface
{
    public function setContenu($contenu);

    public function getContenu();

    public function setData(array $data);

    public function getData();

    public function render($api, $version);

    public function getRender($api, $version);
}
