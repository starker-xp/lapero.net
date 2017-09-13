<?php

namespace Starkerxp\CampaignBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Starkerxp\CampaignBundle\Entity\Template;
use Starkerxp\CampaignBundle\Events;
use Starkerxp\CampaignBundle\Form\Type\TemplateType;
use Starkerxp\StructureBundle\Controller\StructureController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class TemplateController extends StructureController
{
    /**
     * @ApiDoc(
     *      resource=true,
     *      description="List templates.",
     *      section="Campaign",
     *      parameters={
     *          {
     *              "name"="offset",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="starkerxp_structure.doc.offset.result",
     *              "required"="false"
     *          },
     *          {
     *              "name"="limit",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="starkerxp_structure.doc.limit.result",
     *              "required"="false"
     *          },
     *          {
     *              "name"="fields",
     *              "dataType"="string",
     *              "requirement"="\w+",
     *              "description"="starkerxp_structure.doc.list_field.entity",
     *              "required"="false"
     *          },
     *          {
     *              "name"="sort",
     *              "dataType"="string",
     *              "requirement"="\w+",
     *              "description"="starkerxp_structure.doc.sort.result",
     *              "required"="false"
     *          }
     *      },
     *      views = { "default" }
     * )
     *
     */
    public function cgetAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.template");
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


    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Affiche un template.",
     *      section="Campaign",
     *      requirements={
     *          {
     *              "name"="template_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Show an element"
     *          }
     *      },
     *      parameters={
     *          {
     *              "name"="fields",
     *              "dataType"="string",
     *              "requirement"="\w+",
     *              "description"="starkerxp_structure.doc.list_field.entity",
     *              "required"="false"
     *          }
     *      },
     *      views = { "default" }
     * )
     */
    public function getAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.template");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            if (!$entite = $manager->findOneBy(['id' => $request->get('template_id')])) {
                return new JsonResponse(["payload" => $this->translate("entity.not_found", "template")], 404);
            }
        } catch (\Exception $e) {
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }

        $retour = $manager->toArray($entite, $this->getFields($options['fields']));

        return new JsonResponse($retour);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Ajoute un template.",
     *      section="Campaign",
     *      views = { "default" }
     * )
     */
    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.template");
        try {
            $entite = new Template();
            $form = $this->createForm(TemplateType::class, $entite, ['method' => 'POST']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $template = $form->getData();
                $manager->insert($template);
                $this->dispatch(Events::TEMPLATE_CREATED, new GenericEvent($entite));

                return new JsonResponse(["payload" => $this->translate("entity.created", "template")], 201);
            }
        } catch (\Exception $e) {
            $manager->rollback();

            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }

        return new JsonResponse(["payload" => $this->getFormErrors($form)], 400);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Modifie un template.",
     *      section="Campaign",
     *      requirements={
     *          {
     *              "name"="template_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Edit an element."
     *          }
     *      },
     *      views = { "default" }
     * )
     */
    public function putAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.template");
        if (!$entite = $manager->findOneBy(['id' => $request->get('template_id')])) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "template")], 404);
        }
        $manager->beginTransaction();
        try {
            $form = $this->createForm(TemplateType::class, $entite, ['method' => 'PUT']);
            $form->submit($this->getRequestData($request), false);
            if ($form->isValid()) {
                $entite = $form->getData();
                $manager->update($entite);
                $this->dispatch(Events::TEMPLATE_UPDATED, new GenericEvent($entite));

                return new JsonResponse(["payload" => $this->translate("entity.updated", "template")], 204);
            }
        } catch (\Exception $e) {
            $manager->rollback();

            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }

        return new JsonResponse(["payload" => $this->getFormErrors($form)], 400);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Delete a template.",
     *      section="Campaign",
     *      requirements={
     *          {
     *              "name"="template_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Delete an element."
     *          }
     *      },
     *      views = { "default" }
     * )
     */
    public function deleteAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.template");
        if (!$entite = $manager->findOneBy(['id' => $request->get('template_id')])) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "template")], 404);
        }
        try {
            $manager->delete($entite);
        } catch (\Exception $e) {
            $manager->rollback();

            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        $this->dispatch(Events::TEMPLATE_DELETED, new GenericEvent($request->get('template_id')));

        return new JsonResponse(["payload" => $this->translate("entity.deleted", "template")], 204);
    }

}
