<?php

namespace Starkerxp\CampagneBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            )
            ->add(
                'sujet',
                Type\TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['min' => 3]),
                    ],
                ]
            )
            ->add(
                'message',
                Type\TextareaType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['min' => 10]),
                    ],
                ]
            )
            ->add(
                'type',
                Type\ChoiceType::class,
                [
                    "choices"     => [
                        "email",
                        "sms",
                    ],

                ]
            );
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
