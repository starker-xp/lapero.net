<?php

namespace Starkerxp\LeadBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Starkerxp\StructureBundle\Form\Type\AbstractType;
use Starkerxp\StructureBundle\Services\EntityToIdObjectTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeadType extends AbstractType
{

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formulaireTransformer = new EntityToIdObjectTransformer($this->manager, "StarkerxpCampagneBundle:Formulaire");
        if (!$formulaire = $formulaireTransformer->reverseTransform($options['formId'])) {
            // @todo Génération formulaire par défaut.
            return true;
        }
        // @todo Configuration du formulaire dynamique
        foreach ($formulaire->getData() as $field) {
            $builder->add($field['libelle'], $field['type'], $this->getConstraints($field['constraints']));
        }

        return true;
    }

    /**
     * @param $constraints
     *
     * @return array
     */
    private function getConstraints($constraints)
    {
        $export = [];
        foreach ($constraints as $constraint) {
            $export[] = [];
        }

        return $export;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'formId'          => null,
                'csrf_protection' => false,
            ]
        );
        $resolver->setAllowedTypes("formId", ['integer']);
        $resolver->setRequired("formId");
    }

    public function getName()
    {
        return '';
    }
}
