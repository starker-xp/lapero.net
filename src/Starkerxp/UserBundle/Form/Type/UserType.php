<?php

namespace Starkerxp\UserBundle\Form\Type;

use Starkerxp\StructureBundle\Form\Type\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //@todo Gestion des rôles en fonctions de mon rôle actuel.
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


}
