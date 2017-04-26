<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PanierController extends Controller
{

    public function ajouterAuPanierAction(Request $request)
    {
        return new JsonResponse(["ok"],201);

    }

    public function retirerDuPanierAction(Request $request)
    {
    }

    public function voirMonPanierAction(Request $request)
    {
    }

    public function viderMonPanierAction(Request $request)
    {
    }

    public function passerCommandeAction(Request $request)
    {
        return new JsonResponse(["ok"], 201);

    }

    public function payerLaCommandeAction(Request $request)
    {
        return new JsonResponse(["ok"], 201);

    }

    public function paiementAEteValideAction(Request $request)
    {
    }

    public function paiementAEteRefuseAction(Request $request)
    {
    }
}
