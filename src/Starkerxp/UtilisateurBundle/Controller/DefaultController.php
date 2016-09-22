<?php

namespace Starkerxp\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('StarkerxpUtilisateurBundle:Default:index.html.twig');
    }
}
