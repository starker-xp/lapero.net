<?php

namespace Starkerxp\CampagneBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TemplateController extends Controller
{

    public function cgetAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());

            $orderBy = !empty($options['sort']) ? array_map(
                function ($r) {
                    return [substr($r, 1) => substr($r, 0, 1) == '+' ? 'ASC' : 'DESC'];
                },
                explode(',', $options['sort'])
            ) : [];

            $manager->findBy([], $orderBy, $options['limit'], $options['offset']);
        } catch (\Exception $e) {
            return new JsonResponse(["payload" => $e->getMessage()], 400); //400
        }

        return new JsonResponse(["payload" => $options]); //400
    }

    public function resolveParams()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(
            [
                'offset' => 0,
                'limit'  => 15,
                //'fields' => "*",
                'sort'   => "",
                //'filter' => "",
            ]
        );

        return $resolver;
    }

    public function getAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");

        return new JsonResponse(["payload" => []]);//400
    }

    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");
        $options = $manager->getPostOptionResolver()->resolve($request->request->all());

        return new JsonResponse(["payload" => ""], 201);//400
    }

    public function putAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");
        $options = $manager->getPutOptionResolver()->resolve($request->request->all());

        return new JsonResponse(["payload" => ""], 303); //400
    }

    public function deleteAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");

        return new JsonResponse(["payload" => ""], 204); //404 /400
    }

}
