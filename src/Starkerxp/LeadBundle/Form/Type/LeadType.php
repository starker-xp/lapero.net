<?php

namespace Starkerxp\LeadBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Starkerxp\LeadBundle\Entity\Form;
use Starkerxp\StructureBundle\Form\Type\AbstractType;
use Starkerxp\StructureBundle\Services\EntityToIdObjectTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $formulaireTransformer = new EntityToIdObjectTransformer($this->manager, "StarkerxpLeadBundle:Form");
        /** @var Form $form */
        if (!$form = $formulaireTransformer->reverseTransform($options['formId'])) {
            throw new Exception(sprintf("Form '%d' not found", $options['formId']));
        }
        foreach ($form->getConfiguration() as $field) {
            $builder->add($field['libelle'], $this->getFqcn($field['type']), $this->getConstraints($field['constraints']));
        }

        return true;
    }

    public function getFqcn($type)
    {
        if ($type == "Entity") {
            return EntityType::class;
        }
        TextType::class;
        $class = '\\Symfony\\Component\\Form\\Extension\\Core\\Type\\'.$type.'Type';
        if (class_exists($class)) {
            return $class;
        }
        throw new LogicException(sprintf("Form type '%s' not found", $type));
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
