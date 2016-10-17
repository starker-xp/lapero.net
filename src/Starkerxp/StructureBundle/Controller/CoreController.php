<?php

namespace Starkerxp\StructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function getRepository($entityFQCN)
    {
        return $this->getEntityManager()->getRepository($entityFQCN);
    }

    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
