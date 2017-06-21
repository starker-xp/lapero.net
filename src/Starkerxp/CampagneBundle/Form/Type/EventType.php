<?php

namespace Starkerxp\CampagneBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Starkerxp\CampagneBundle\Entity\Campagne;
use Starkerxp\CampagneBundle\Entity\Template;
use Starkerxp\CampagneBundle\Repository\CampagneRepository;
use Starkerxp\CampagneBundle\Repository\TemplateRepository;
use Starkerxp\StructureBundle\Services\EntityToIdObjectTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class EventType extends AbstractType
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
        $builder->add(
            'campagne',
            EntityType::class,
            [
                'class'         => Campagne::class,
                'multiple'      => false,
                'required'      => false,
                'query_builder' => function (CampagneRepository $repository) {
                    return $repository->getQueryListe();
                },
                'constraints'   => [
                    new Constraints\NotBlank(),
                ],
            ]
        );
        $builder->get('campagne')->addModelTransformer(new EntityToIdObjectTransformer($this->manager, "StarkerxpCampagneBundle:Campagne"));

        $builder->add(
            'template',
            EntityType::class,
            [
                'class'         => Template::class,
                'multiple'      => false,
                'required'      => false,
                'query_builder' => function (TemplateRepository $repository) {
                    return $repository->getQueryListe();
                },
                'constraints'   => [
                    new Constraints\NotBlank(),
                ],
            ]
        );
        $builder->get('template')->addModelTransformer(new EntityToIdObjectTransformer($this->manager, "StarkerxpCampagneBundle:Template"));
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
