<?php

namespace Starkerxp\CampagneBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Starkerxp\CampagneBundle\Entity\Campagne;
use Starkerxp\CampagneBundle\Events;
use Starkerxp\CampagneBundle\Form\Type\CampagneType;
use Starkerxp\StructureBundle\Controller\StructureController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CampagneController extends StructureController
{
	/**
     * @ApiDoc(
     *      resource=true,
     *      description="Liste les campagnes.",
     *      section="Campagne",
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
        $manager = $this->get("starkerxp_campagne.manager.campagne");
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
     *      description="Affiche une campagne.",
     *      section="Campagne",
     *      requirements={
     *          {
     *              "name"="campagne_id",
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
        $manager = $this->get("starkerxp_campagne.manager.campagne");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            if (!$entite = $manager->findOneBy(['id' => $request->get('campagne_id')])) {
                return new JsonResponse(["payload" => $this->translate("entity.not_found", "campagne")], 404);
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
     *      description="Ajoute une campagne.",
     *      section="Campagne",
     *      views = {"default"}
     * )
     */
    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.campagne");
        try {
            $entite = new Campagne();
            $form = $this->createForm(CampagneType::class, $entite, ['method' => 'POST']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $entite = $form->getData();
                $manager->insert($entite);
                $this->dispatch(Events::CAMPAGNE_CREATED, new GenericEvent($entite));

                return new JsonResponse(["payload" => $this->translate("entity.created", "campagne"), "token" => $entite->getId()], 201);
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
     *      description="Modifie une campagne.",
     *      section="Campagne",
     *      requirements={
     *          {
     *              "name"="campagne_id",
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
        $manager = $this->get("starkerxp_campagne.manager.campagne");
        if (!$entite = $manager->findOneBy(['id' => $request->get('campagne_id')])) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "campagne")], 404);
        }
        $manager->beginTransaction();
        try {
            $form = $this->createForm(CampagneType::class, $entite, ['method' => 'PUT']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $entite = $form->getData();
                $manager->update($entite);
                $this->dispatch(Events::CAMPAGNE_UPDATED, new GenericEvent($entite));

                return new JsonResponse(["payload" => $this->translate("entity.updated", "campagne")], 204);
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
     *      description="Supprime une campagne.",
     *      section="Campagne",
     *      requirements={
     *          {
     *              "name"="campagne_id",
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
        $manager = $this->get("starkerxp_campagne.manager.campagne");
        if (!$entite = $manager->findOneBy(['id' => $request->get('campagne_id')])) {
            return new JsonResponse(["payload" => $this->translate("entity.not_found", "campagne")], 404);
        }
        try {
            $manager->delete($entite);
        } catch (\Exception $e) {
            $manager->rollback();

            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        $this->dispatch(Events::CAMPAGNE_DELETED, new GenericEvent($request->get('campagne_id')));

        return new JsonResponse(["payload" => $this->translate("entity.deleted", "campagne")], 204);
    }

} 
