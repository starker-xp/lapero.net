<?php

namespace AppBundle\Controller;

use Starkerxp\StructureBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends CoreController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscriptionAction(Request $request)
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
     * @Route("/mon-compte/modifier-mon-profil", name="modifierMonCompte")
     */
    public function modifierMonProfilAction(Request $request)
    {

    }

    public function ajouterAuPanierAction(Request $request)
    {

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

    }

    public function payerLaCommandeAction(Request $request)
    {

    }

    public function paiementAEteValideAction(Request $request)
    {

    }

    public function paiementAEteRefuseAction(Request $request)
    {

    }

}
