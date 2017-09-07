<?php

namespace Starkerxp\CampaignBundle\Form\Type;

use Starkerxp\StructureBundle\Form\Type\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                Type\ChoiceType::class,
                [
                    "choices"     => [
                        "email",
                        "sms",
                        "courrier",
                    ],
                ]
            )
            ->add(
                'name',
                Type\TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['min' => 3]),
                    ],
                ]
            )
            ->add(
                'object',
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
            ;
    }

}
