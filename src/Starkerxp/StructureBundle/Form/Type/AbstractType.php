<?php

namespace Starkerxp\StructureBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType extends \Symfony\Component\Form\AbstractType
{
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
