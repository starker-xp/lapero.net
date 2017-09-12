<?php

namespace Starkerxp\CampaignBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Starkerxp\CampaignBundle\Entity\Event;
use Starkerxp\CampaignBundle\Form\Type\EventType;
use Starkerxp\StructureBundle\Controller\StructureController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class EventController extends StructureController
{
    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Show events list.",
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
     */
    public function cgetAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.event");
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
     *      description="Show event.",
     *      section="Campaign",
     *      requirements={
     *          {
     *              "name"="campaign_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Show an element"
     *          },
     *          {
     *              "name"="event_id",
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
        $manager = $this->get("starkerxp_campaign.manager.event");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            $event = $manager->findOneBy(['id' => $request->get('id')]);
        } catch (\Exception $e) {
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        if (!$event instanceof Event) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "event")], 404);
        }
        $retour = $manager->toArray($event, $this->getFields($options['fields']));

        return new JsonResponse($retour);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Ajoute un event.",
     *      section="Campaign",
     *      views = { "default" }
     * )
     */
    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.event");
        try {
            $event = new Event();
            $form = $this->createForm(EventType::class, $event, ['method' => 'POST']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $event = $form->getData();
                $event->setUuid($this->getUuid());
                $manager->insert($event);

                return new JsonResponse(["payload" => $this->translate("entity.created", "event")], 201);
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
     *      description="Edit event.",
     *      section="Campaign",
     *      requirements={
     *          {
     *              "name"="campaign_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Permet de modifier un élément de la campaign"
     *          },
     *          {
     *              "name"="event_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="edit an elements"
     *          }
     *      },
     *      views = { "default" }
     * )
     */
    public function putAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.event");
        $event = $manager->find($request->get('id'));
        if (!$event instanceof Event) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "event")], 404);
        }
        $manager->beginTransaction();
        try {
            $form = $this->createForm(EventType::class, $event, ['method' => 'PUT']);
            $form->submit($this->getRequestData($request), false);
            if ($form->isValid()) {
                $event = $form->getData();
                $manager->update($event);

                return new JsonResponse(["payload" => $this->translate("entity.updated", "event")], 204);
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
     *      description="Delete event.",
     *      section="Campaign",
     *      requirements={
     *          {
     *              "name"="campaign_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Permet de supprimer un élément de la campaign"
     *          },
     *          {
     *              "name"="event_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Delete an elements"
     *          }
     *      },
     *      views = { "default" }
     * )
     */
    public function deleteAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.event");
        $event = $manager->find($request->get('id'));
        if (!$event instanceof Event) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "event")], 404);
        }
        try {
            $manager->delete($event);
        } catch (\Exception $e) {
            $manager->rollback();

            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }

        return new JsonResponse(["payload" => $this->translate("entity.deleted", "event")], 204);
    }

}
