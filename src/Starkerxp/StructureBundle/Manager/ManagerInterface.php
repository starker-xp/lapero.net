<?php

namespace Starkerxp\StructureBundle\Manager;

use Starkerxp\StructureBundle\Entity\Entity;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ManagerInterface
{
    public function insert(Entity $object);

    public function update(Entity $object);

    public function getSupport(Entity $object);

    /**
     * @param OptionsResolver|null $resolver
     *
     */
    public function getPostOptionResolver(OptionsResolver $resolver = null);

    /**
     * @param OptionsResolver|null $resolver
     * @return mixed
     */
    public function getPutOptionResolver(OptionsResolver $resolver = null);
}
