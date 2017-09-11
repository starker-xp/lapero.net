<?php

namespace Starkerxp\UserBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Starkerxp\StructureBundle\Controller\StructureController;
use Starkerxp\UserBundle\Entity\User;
use Starkerxp\UserBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends StructureController
{
    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Liste les users.",
     *      section="User",
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
        $manager = $this->get("starkerxp_user.manager.user");
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
     *      description="Affiche un user.",
     *      section="User",
     *      requirements={
     *          {
     *              "name"="user_id",
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
        $manager = $this->get("starkerxp_user.manager.user");
        try {
            $options = $this->resolveParams()->resolve($request->query->all());
            $user = $manager->findOneBy(['id' => $request->get('user_id')]);
        } catch (\Exception $e) {
            return new JsonResponse(["payload" => $e->getMessage()], 400);
        }
        if (!$user instanceof User) {
            return new JsonResponse(["payload" => $this->translate("user.entity.not_found", "user")], 404);
        }
        $retour = $manager->toArray($user, $this->getFields($options['fields']));

        return new JsonResponse($retour);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *      description="Ajoute un user.",
     *      section="User",
     *      requirements={
     *          {
     *              "name"="email",
     *              "dataType"="string",
     *              "requirement"="\w+",
     *              "description"="Définit l'identifiant de connexion"
     *          },
     *          {
     *              "name"="type",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="1 - User / 2 - Api"
     *          },
     *      },
     *      views = { "default" }
     * )
     */
    public function postAction(Request $request)
    {
        $manager = $this->get("starkerxp_user.manager.user");
        try {
            $form = $this->createForm(UserType::class, [], ['method' => 'POST']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $user = $form->getData();
                $user->setUuid($this->getUuid());
                $manager->insert($user);

                return new JsonResponse(["payload" => $this->translate("user.entity.created", "user")], 201);
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
     *      description="Modifie un user.",
     *      section="User",
     *      requirements={
     *          {
     *              "name"="user_id",
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
        $manager = $this->get("starkerxp_user.manager.user");
        $user = $manager->find($request->get('user_id'));
        if (!$user instanceof User) {
            return new JsonResponse(["payload" => $this->translate("user.entity.not_found", "user")], 404);
        }
        // Un user ne peut modifier un autre user sauf si ce dernier est un super admin.
        if ($this->getUser()->getId() != $user->getId() && !$this->isGranted("ROLE_SUPER_ADMIN")) {
            return new JsonResponse(["payload" => $this->translate("user.entity.not_updated_is_not_admin", "user")], 400);
        }
        $manager->beginTransaction();
        try {
            $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
            $form->submit($this->getRequestData($request));
            if ($form->isValid()) {
                $user = $form->getData();
                $manager->update($user);

                return new JsonResponse(["payload" => $this->translate("user.entity.updated", "user")], 204);
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
     *      description="Delete a user.",
     *      section="User",
     *      requirements={
     *          {
     *              "name"="user_id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Delete an element."
     *          }
     *      },
     *      views = { "default" }
     * )
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $manager = $this->get("starkerxp_user.manager.user");
        $user = $manager->find($request->get('user_id'));
        if (!$user instanceof User) {
            return new JsonResponse(["payload" => $this->translate("user.entity.not_found", "user")], 404);
        }

        return new JsonResponse(["payload" => $this->translate("user.entity.deleted", "user")], 204);
    }

}
