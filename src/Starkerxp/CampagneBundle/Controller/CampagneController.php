<?php

namespace Starkerxp\CampagneBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Starkerxp\CampagneBundle\Entity\Campagne;
use Starkerxp\CampagneBundle\Form\Type\CampagneType;
use Starkerxp\StructureBundle\Controller\StructureController;
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
     *      views = { "default"}
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
     *      views = { "default"}
     * )
     */
    public function getAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.campagne");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            $campagne = $manager->findOneBy(['id' => $request->get('campagne_id')]);
        } catch (\Exception $e) {
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        if (!$campagne instanceof Campagne) {
            return new JsonResponse(["payload" => $this->translate("campagne.entity.not_found", "campagne")], 404);
        }
        $retour = $manager->toArray($campagne, $this->getFields($options['fields']));

        return new JsonResponse($retour);
    }

	/**
     * @ApiDoc(
     *      resource=true,
     *      description="Ajoute une campagne.",
     *      section="Campagne",
     *      views = { "default"}
     * )
     */
    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.campagne");
        try {
            $campagne = new Campagne();
            $form = $this->createForm(CampagneType::class, $campagne, ['method' => 'POST']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $campagne = $form->getData();
                $campagne->setUuid($this->getUuid());
                $manager->insert($campagne);
                return new JsonResponse(["payload" => $this->translate("campagne.entity.created", "campagne")], 201);
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
     *      views = { "default"}
     * )
     */
    public function putAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.campagne");
        $campagne = $manager->find($request->get('campagne_id'));
        if (!$campagne instanceof Campagne) {
            return new JsonResponse(["payload" => $this->translate("campagne.entity.not_found", "campagne")], 404);
        }
        $manager->beginTransaction();
        try {
            $form = $this->createForm(CampagneType::class, $campagne, ['method' => 'PUT']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $campagne = $form->getData();
                $manager->update($campagne);
                return new JsonResponse(["payload" => $this->translate("campagne.entity.updated", "campagne")], 204);
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
     *      views = { "default"}
     * )
     */
    public function deleteAction(Request $request)
    {
        $manager = $this->get("starkerxp_campagne.manager.campagne");
        $campagne = $manager->find($request->get('campagne_id'));
        if (!$campagne instanceof Campagne) {
            return new JsonResponse(["payload" => $this->translate("campagne.entity.not_found", "campagne")], 404);
        }
        try {
            $manager->delete($campagne);
        } catch (\Exception $e) {
            $manager->rollback();
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        return new JsonResponse(["payload" => $this->translate("campagne.entity.deleted", "campagne")], 204);
    }

} 
