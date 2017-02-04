<?php

namespace Starkerxp\CampagneBundle\Controller;

use Starkerxp\CampagneBundle\Entity\Template;
use Starkerxp\CampagneBundle\Form\Type\TemplateType;
use Starkerxp\StructureBundle\Controller\StructureController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class TemplateController extends StructureController
{

    public function cgetAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            $orderBy = $this->getOrderBy($options['sort']);
            $resultSets = $manager->findBy([], $orderBy, $options['limit'], $options['offset']);
        } catch (\Exception $e) {
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        if (empty($resultSets)) {
            return new JsonResponse([]);
        }
        $retour = array_map(
            function ($element) use ($manager, $options) {
                return $manager->toArray($element, $this->getFields($options['fields']));
            },
            $resultSets
        );
        return new JsonResponse($retour);
    }

    public function getAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            $template = $manager->findOneBy(['id' => $request->get('id')]);
        } catch (\Exception $e) {
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        if (!$template instanceof Template) {
            return new JsonResponse(["payload" => $this->translate("template.entity.not_found", "template")], 404);
        }
        $retour = $manager->toArray($template, $this->getFields($options['fields']));

        return new JsonResponse($retour);
    }

    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");
        try {
            $template = new Template();
            $form = $this->createForm(TemplateType::class, $template, ['method' => 'POST']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $template = $form->getData();
                $template->setUuid($this->getUuid());
                $manager->insert($template);
                return new JsonResponse(["payload" => $this->translate("template.entity.created", "template")], 201);
            }
        } catch (\Exception $e) {
            $manager->rollback();
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }

        return new JsonResponse(["payload" => $this->getFormErrors($form)], 400);
    }

    public function putAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");
        $template = $manager->find($request->get('id'));
        if (!$template instanceof Template) {
            return new JsonResponse(["payload" => $this->translate("template.entity.not_found", "template")], 404);
        }
        $manager->beginTransaction();
        try {
            $form = $this->createForm(TemplateType::class, $template, ['method' => 'PUT']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $template = $form->getData();
                $manager->update($template);
                return new JsonResponse(["payload" => $this->translate("template.entity.updated", "template")], 204);
            }
        } catch (\Exception $e) {
            $manager->rollback();
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        return new JsonResponse(["payload" => $this->getFormErrors($form)], 400);
    }

    public function deleteAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.template");
        $template = $manager->find($request->get('id'));
        if (!$template instanceof Template) {
            return new JsonResponse(["payload" => $this->translate("template.entity.not_found", "template")], 404);
        }
        try {
            $manager->delete($template);
        } catch (\Exception $e) {
            $manager->rollback();
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        return new JsonResponse(["payload" => $this->translate("template.entity.deleted", "template")], 204);
    }

}
