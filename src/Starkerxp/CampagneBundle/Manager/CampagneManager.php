<?php

namespace Starkerxp\CampagneBundle\Manager;

use Starkerxp\CampagneBundle\Entity\Campagne;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampagneManager extends AbstractManager
{
    public function getSupport(Entity $object)
    {
        return $object instanceof Campagne;
    }

    public function getPostOptionResolver(OptionsResolver $resolver = null)
    {
        if (empty($resolver)) {
            $resolver = new OptionsResolver();
        }
        $resolver->setRequired(
            [
                'name',
                'type',
                'template',
                'send_at',
            ]
        );

        return $resolver;
    }
}
