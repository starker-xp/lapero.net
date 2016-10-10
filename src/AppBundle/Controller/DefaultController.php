<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Starkerxp\CampagneBundle\Services\Render\RenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /** @var RenderInterface $renderManager */
        $renderManager = $this->get('starkerxp_campagne.manager.render');
        $renderManager->setData(['prenom' => 'guillaume']);
        $monTexte = 'Ceci est mon texte {{prenom|capitalize}} ! Tu ne pourras rien y faire démonïtus.';
        $renderManager->setContenu($monTexte);
        $retour = $renderManager->render('buzzexpert', 'txt');

        return new JsonResponse(
            [
                'nombreService' => count($renderManager->getRenderService()),
                'textInitial' => $monTexte,
                'text' => $retour,
            ]
        );
    }

    public function contactAction(Request $request){

    }

    public function mentionsLegalesAction(Request $request){

    }


}
