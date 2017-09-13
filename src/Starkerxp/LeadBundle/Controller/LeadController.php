<?php

namespace Starkerxp\LeadBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Starkerxp\LeadBundle\Entity\Lead;
use Starkerxp\LeadBundle\Events;
use Starkerxp\LeadBundle\Form\Type\LeadType;
use Starkerxp\StructureBundle\Controller\StructureController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class LeadController extends StructureController
{
    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Liste les leads.",
     *      section="Lead",
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
        $manager = $this->get("starkerxp_lead.manager.lead");
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
     *      description="Affiche un lead.",
     *      section="Lead",
     *      requirements={
     *          {
     *              "name"="lead_id",
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
        $manager = $this->get("starkerxp_lead.manager.lead");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            /** @var Lead $entite */
            if (!$entite = $manager->findOneBy(['id' => $request->get('lead_id')])) {
                return new JsonResponse(["payload" => $this->translate("entity.not_found", "lead")], 404);
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
     *      description="Ajoute un lead.",
     *      section="Lead",
     *      views = { "default" }
     * )
     */
    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_lead.manager.lead");
        try {
            $entite = new Lead();
            $form = $this->createForm(LeadType::class, $entite, ['method' => 'POST']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $lead = $form->getData();
                $manager->insert($lead);
                $this->dispatch(Events::LEAD_CREATED, new GenericEvent($lead));

                return new JsonResponse(["payload" => $this->translate("entity.created", "lead")], 201);
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
     *      description="Edit lead.",
     *      section="Lead",
     *      requirements={
     *          {
     *              "name"="lead_id",
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
        $manager = $this->get("starkerxp_lead.manager.lead");
        if (!$entite = $manager->findOneBy(['id' => $request->get('lead_id')])) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "lead")], 404);
        }
        $manager->beginTransaction();
        try {
            $form = $this->createForm(LeadType::class, $entite, ['method' => 'PUT']);
            $form->submit($this->getRequestData($request), false);
            if ($form->isValid()) {
                $entite = $form->getData();
                $manager->update($entite);
                $this->dispatch(Events::LEAD_UPDATED, new GenericEvent($entite));

                return new JsonResponse(["payload" => $this->translate("entity.updated", "campaign")], 204);
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
     *      description="Edit lead.",
     *      section="Lead",
     *      views = { "default" }
     * )
     */
    public function putWithoutIdAction(Request $request)
    {
        $data = $this->getRequestData($request);
        $manager = $this->get("starkerxp_lead.manager.lead");
        if (!$entite = $manager->findOneBy(['origin' => $data['origin'], 'externalReference' => $data['external_reference']])) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "lead")], 404);
        }
        $response = $this->forward(
            'StarkerxpLeadBundle:Lead:put',
            [
                'lead_id' => $entite->getId(),
            ]
        );

        return $response;
    }
}
