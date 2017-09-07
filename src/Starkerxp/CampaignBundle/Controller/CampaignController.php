<?php

namespace Starkerxp\CampaignBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Starkerxp\CampaignBundle\Entity\Campaign;
use Starkerxp\CampaignBundle\Events;
use Starkerxp\CampaignBundle\Form\Type\CampaignType;
use Starkerxp\StructureBundle\Controller\StructureController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CampaignController extends StructureController
{
	/**
     * @ApiDoc(
     *      resource=true,
     *      description="Show campaigns list.",
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
        $manager = $this->get("starkerxp_campaign.manager.campaign");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            $orderBy = $this->getOrderBy($options['sort']);
            $resultSets = $manager->findBy([], $orderBy, $options['limit'], $options['offset']);
        } catch (\Exception $e) {
            return new JsonResponse(["payload" => $e->getMessage()], 400);
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
     *      description="Show campaign.",
     *      section="Campaign",
     *      requirements={
     *          {
     *              "name"="campaign_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Permet d'afficher l'élément choisis"
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
        $manager = $this->get("starkerxp_campaign.manager.campaign");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            if (!$entite = $manager->findOneBy(['id' => $request->get('campaign_id')])) {
                return new JsonResponse(["payload" => $this->translate("entity.not_found", "campaign")], 404);
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
     *      description="Add campaign.",
     *      section="Campaign",
     *      views = {"default"}
     * )
     */
    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.campaign");
        try {
            $entite = new Campaign();
            $form = $this->createForm(CampaignType::class, $entite, ['method' => 'POST']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $entite = $form->getData();
                $manager->insert($entite);
                $this->dispatch(Events::CAMPAIGN_CREATED, new GenericEvent($entite));

                return new JsonResponse(["payload" => $this->translate("entity.created", "campaign"), "token" => $entite->getId()], 201);
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
     *      description="Edit campaign.",
     *      section="Campaign",
     *      requirements={
     *          {
     *              "name"="campaign_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Permet de modifier l'élément choisi."
     *          }
     *      },
     *      views = { "default" }
     * )
     */
    public function putAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.campaign");
        if (!$entite = $manager->findOneBy(['id' => $request->get('campaign_id')])) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "campaign")], 404);
        }
        $manager->beginTransaction();
        try {
            $form = $this->createForm(CampaignType::class, $entite, ['method' => 'PUT']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $entite = $form->getData();
                $manager->update($entite);
                $this->dispatch(Events::CAMPAIGN_UPDATED, new GenericEvent($entite));

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
     *      description="Delete campaign.",
     *      section="Campaign",
     *      requirements={
     *          {
     *              "name"="campaign_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Permet de supprimer l'élément choisi."
     *          }
     *      },
     *      views = { "default" }
     * )
     */
    public function deleteAction(Request $request)
    {
        $manager = $this->get("starkerxp_campaign.manager.campaign");

        if (!$entite = $manager->findOneBy(['id' => $request->get('campaign_id')])) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "campaign")], 404);
        }
        try {
            $manager->delete($entite);
        } catch (\Exception $e) {
            $manager->rollback();

            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        $this->dispatch(Events::CAMPAIGN_DELETED, new GenericEvent($request->get('campaign_id')));

        return new JsonResponse(["payload" => $this->translate("entity.deleted", "campaign")], 204);
    }

} 
