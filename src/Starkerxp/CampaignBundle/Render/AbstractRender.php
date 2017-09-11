<?php

namespace Starkerxp\CampaignBundle\Render;

/**
 * Description of AbstractRender.
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

    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
