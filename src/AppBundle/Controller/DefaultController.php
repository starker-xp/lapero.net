<?php

namespace AppBundle\Controller;

use Starkerxp\StructureBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends CoreController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $html = <<<'EOF'
<html><style>background-color:black;</style><body><div style="text-align:center;">{{prenom|capitalize}}</div>Ceci[@e] est mon <span class="bold">contenu</span>. <p class="text-faded">Design and code an email that <strong>works on every device</strong> and client is a huge and <strong>demanding work</strong> also for professionals.<br>Mosaico allows you to realize <strong>a beautiful and effective template</strong>,<br> without a <strong>team of professionals</strong> and hours of testing to let it works everywhere.</p> Vous pouvez le télécharger <a href="http://google.com/test.php">ici</a></body></html>
EOF;
        /** @var \Starkerxp\CampagneBundle\Render\RenderManager $renderManager */
        $renderManager = $this->get('starkerxp_campagne.manager.render');
        $renderManager->setData(['prenom' => 'guillaume']);
        $renderManager->setContenu($html);
        $renderManager->setApi('octosend');
        $renderManager->setVersion('txt');
        $retour = $renderManager->render();

        return new JsonResponse(
            [
                'nombreService' => count($renderManager->getRenderService()),
                'textInitial'   => $html,
                'text'          => $retour,
            ]
        );
    }

    public function contactAction(Request $request)
    {
    }

    public function mentionsLegalesAction(Request $request)
    {
    }
}
