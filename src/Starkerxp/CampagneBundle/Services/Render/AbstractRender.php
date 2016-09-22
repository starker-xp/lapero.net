<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Starkerxp\CampagneBundle\Services\Render;

/**
 * Description of AbstractRender
 *
 * @author DIEU
 */
abstract class AbstractRender implements RenderInterface
{

    protected $contenu;
    protected $data;

    public function getContenu()
    {
        return $this->contenu;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

}
