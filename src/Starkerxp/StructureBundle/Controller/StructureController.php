<?php

namespace Starkerxp\StructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;


class StructureController extends Controller
{
    /**
     * Permet de gérer les paramètres par défaut pour la gestion de l'api.
     *
     * @return OptionsResolver
     */
    protected function resolveParams()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(
            [
                'offset' => 0,
                'limit'  => 15,
                'fields' => "*",
                'sort'   => "",
                //'filter' => "",
            ]
        );

        return $resolver;
    }

    /**
     * Génère un tableau d'orderBy afin d'afficher les résultats comme voulu.
     *
     * @param $sort
     *
     * @return array
     */
    protected function getOrderBy($sort)
    {
        if (empty($sort)) {
            return [];
        }
        $tableauSort = explode(',', $sort);
        $export = [];
        foreach ($tableauSort as $element) {
            $order = substr($element, 0, 1) == '-' ? 'DESC' : 'ASC';
            $export[$order == "DESC" ? substr($element, 1) : trim($element)] = $order;
        }

        return $export;
    }


    /**
     * Permet de gérer les champs quer l'api va retourner. Par défaut elle retournera tous les champs.
     *
     * @param $fields
     *
     * @return array
     */
    protected function getFields($fields)
    {
        if ($fields == "*") {
            return [];
        }

        return explode(",", $fields);
    }

    /**
     * Retourne l'entity manager de la connexion defaut.
     *
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Retourne les messages d'erreur issu d'un formulaire.
     *
     * @param $form
     *
     * @return array
     */
    protected function getFormErrors($form)
    {
        $errors = $this->get("starkerxp_structure.services.form_errors")->getFormErrors($form);

        return $errors;
    }

    /**
     * Retourne un uuid.
     *
     * @return string
     */
    protected function getUuid()
    {
        return (\Ramsey\Uuid\Uuid::uuid4())->toString();
    }

    /**
     * Permet de traduire un message.
     *
     * @param $id
     * @param null $domain
     * @param array $parameters
     *
     * @return string
     */
    protected function translate($id, $domain = null, array $parameters = [])
    {
        return $this->get('translator')->trans($id, $parameters, $domain);
    }

    /**
     * Retourne des données envoyer en json ou POST/PUT.
     *
     * @param Request $request
     *
     * @return array|mixed
     */
    protected function getRequestData(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data)) {
            $data = $request->request->all();
        }

        return $data;
    }

    /**
     * @return ContainerInterface|null
     */
    protected function getContainer()
    {
        return $this->container;
    }


}
