<?php

namespace Starkerxp\LeadBundle\Form\Type;

use Starkerxp\LeadBundle\Constraints\LeadExist;
use Starkerxp\LeadBundle\Constraints\LeadNotExist;
use Starkerxp\StructureBundle\Form\Type\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class LeadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'origin',
                Type\TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['min' => 3]),
                    ],
                ]
            )
            ->add(
                'external_reference',
                Type\TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['min' => 1]),
                        $options['method'] === "PUT" ? new LeadNotExist() : new LeadExist(),
                    ],
                ]
            )
            ->add(
                'product',
                Type\TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['min' => 3]),
                    ],
                ]
            )
            ->add(
                'date_event',
                Type\DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd HH:mm:ss',
                ]
            )
            ->add(
                'ip_address',
                Type\TextType::class,
                [
                    "constraints" => [
                        new Constraints\Ip(),
                    ],
                ]
            )
            ->add('pixel', Type\IntegerType::class);

    }

}
