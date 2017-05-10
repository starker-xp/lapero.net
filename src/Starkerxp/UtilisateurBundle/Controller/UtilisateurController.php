<?php

namespace Starkerxp\UtilisateurBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Starkerxp\UtilisateurBundle\Entity\Utilisateur;
use Starkerxp\UtilisateurBundle\Form\Type\UtilisateurType;
use Starkerxp\StructureBundle\Controller\StructureController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class UtilisateurController extends StructureController
{
	/**
     * @ApiDoc(
     *      resource=true,
     *      description="Liste les utilisateurs.",
     *      section="Utilisateur",
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
     *      }
     * )
     * )
     */
    public function cgetAction(Request $request)
    {
        $manager = $this->get("starkerxp_utilisateur.manager.utilisateur");
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
     *      description="Affiche un utilisateur.",
     *      section="Utilisateur",
	 *      requirements={
     *          {
     *              "name"="utilisateur_id",
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
     *      }
     * )
     */
    public function getAction(Request $request)
    {
        $manager = $this->get("starkerxp_utilisateur.manager.utilisateur");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            $utilisateur = $manager->findOneBy(['id' => $request->get('utilisateur_id')]);
        } catch (\Exception $e) {
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        if (!$utilisateur instanceof Utilisateur) {
            return new JsonResponse(["payload" => $this->translate("utilisateur.entity.not_found", "utilisateur")], 404);
        }
        $retour = $manager->toArray($utilisateur, $this->getFields($options['fields']));

        return new JsonResponse($retour);
    }

	/**
     * @ApiDoc(
     *      resource=true,
     *      description="Ajoute un utilisateur.",
     *      section="Utilisateur"
     * )
     */
    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_utilisateur.manager.utilisateur");
        try {
            $utilisateur = new Utilisateur();
            $form = $this->createForm(UtilisateurType::class, $utilisateur, ['method' => 'POST']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $utilisateur = $form->getData();
                $utilisateur->setUuid($this->getUuid());
                $manager->insert($utilisateur);
                return new JsonResponse(["payload" => $this->translate("utilisateur.entity.created", "utilisateur")], 201);
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
     *      description="Modifie un utilisateur.",
     *      section="Utilisateur",
	 *      requirements={
     *          {
     *              "name"="utilisateur_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Permet de modifier l'élément choisi."
     *          }
     *      }
     * )
     */
    public function putAction(Request $request)
    {
        $manager = $this->get("starkerxp_utilisateur.manager.utilisateur");
        $utilisateur = $manager->find($request->get('utilisateur_id'));
        if (!$utilisateur instanceof Utilisateur) {
            return new JsonResponse(["payload" => $this->translate("utilisateur.entity.not_found", "utilisateur")], 404);
        }
        $manager->beginTransaction();
        try {
            $form = $this->createForm(UtilisateurType::class, $utilisateur, ['method' => 'PUT']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $utilisateur = $form->getData();
                $manager->update($utilisateur);
                return new JsonResponse(["payload" => $this->translate("utilisateur.entity.updated", "utilisateur")], 204);
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
     *      description="Supprime un utilisateur.",
     *      section="Utilisateur",
	 *      requirements={
     *          {
     *              "name"="utilisateur_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Permet de supprimer l'élément choisi."
     *          }
     *      }
     * )
     */
    public function deleteAction(Request $request)
    {
        $manager = $this->get("starkerxp_utilisateur.manager.utilisateur");
        $utilisateur = $manager->find($request->get('utilisateur_id'));
        if (!$utilisateur instanceof Utilisateur) {
            return new JsonResponse(["payload" => $this->translate("utilisateur.entity.not_found", "utilisateur")], 404);
        }
        return new JsonResponse(["payload" => $this->translate("utilisateur.entity.deleted", "utilisateur")], 204);
    }

} 
