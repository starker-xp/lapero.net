<?php

namespace Starkerxp\UtilisateurBundle\Form\Type;

use Starkerxp\StructureBundle\Form\Type\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class UtilisateurType extends AbstractType
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


}
