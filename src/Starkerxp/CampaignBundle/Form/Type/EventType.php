<?php

namespace Starkerxp\CampaignBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Starkerxp\CampaignBundle\Entity\Campaign;
use Starkerxp\CampaignBundle\Entity\Template;
use Starkerxp\CampaignBundle\Repository\CampaignRepository;
use Starkerxp\CampaignBundle\Repository\TemplateRepository;
use Starkerxp\StructureBundle\Services\EntityToIdObjectTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Starkerxp\StructureBundle\Form\Type\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            'campaign',
            EntityType::class,
            [
                'class' => Campaign::class,
                'multiple' => false,
                'required' => false,
                'query_builder' => function (CampaignRepository $repository) {
                    return $repository->getQueryListe();
                },
                'constraints' => [
                    new Constraints\NotBlank(),
                ],
            ]
        );
        $builder->get('campaign')->addModelTransformer(new EntityToIdObjectTransformer($this->manager, "StarkerxpCampaignBundle:Campaign"));

        $builder->add(
            'template',
            EntityType::class,
            [
                'class' => Template::class,
                'multiple' => false,
                'required' => false,
                'query_builder' => function (TemplateRepository $repository) {
                    return $repository->getQueryListe();
                },
                'constraints' => [
                    new Constraints\NotBlank(),
                ],
            ]
        );
        $builder->get('template')->addModelTransformer(new EntityToIdObjectTransformer($this->manager, "StarkerxpCampaignBundle:Template"));
    }

}
