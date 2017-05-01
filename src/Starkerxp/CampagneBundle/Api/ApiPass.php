<?php

namespace Starkerxp\CampagneBundle\Api;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ApiPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('starkerxp_campagne.api.manager');
        foreach (array_keys($container->findTaggedServiceIds('starkerxp_campagne.api')) as $id) {
            $definition->addMethodCall('addApiService', [new Reference($id)]);
        }
    }
}
