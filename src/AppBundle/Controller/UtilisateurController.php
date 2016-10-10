<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Starkerxp\CampagneBundle\Services\Render\RenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends Controller
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function indexAction(Request $request)
    {
        return new JsonResponse();
    }


    /**
     * @Route("/mon-compte/alertes-et-newsletter", name="alerte-newsletter")
     */
    public function gererMesOptinsAction(Request $request)
    {

    }

    /**
     * @Route("/mon-compte/supprimer-mon-compte", name="supprimerMonCompte")
     */
    public function supprimerMonCompteAction(Request $request)
    {

    }

    /**
     * @Route("/mon-compte/modifier", name="modifierMonCompte")
     */
    public function modifierMonProfilAction(Resquest $request)
    {

    }

}
