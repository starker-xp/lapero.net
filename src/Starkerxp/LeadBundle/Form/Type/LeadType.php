<?php

namespace Starkerxp\LeadBundle\Form\Type;

use Starkerxp\StructureBundle\Form\Type\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class LeadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*
		// A titre d'exemple.
		$builder
            ->add(
                'nom',
                Type\TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['min' => 3]),
                    ],
                ]
            );
		*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => false,
            ]
        );
    }

    public function getName()
    {
        return '';
    }
}
