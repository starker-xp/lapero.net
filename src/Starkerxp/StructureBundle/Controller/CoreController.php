<?php

namespace Starkerxp\StructureBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Starkerxp\CampagneBundle\Services\Render\RenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
