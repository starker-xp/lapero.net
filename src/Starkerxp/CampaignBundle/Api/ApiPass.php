<?php

namespace Starkerxp\CampaignBundle\Api;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ApiPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definitionId = 'starkerxp_campaign.api.manager';
        if (!($container->has($definitionId))) {
            return false;
        }
        $definition = $container->findDefinition($definitionId);
        foreach (array_keys($container->findTaggedServiceIds('starkerxp_campaign.api')) as $id) {
            $definition->addMethodCall('addApiService', [new Reference($id)]);
        }
    }
}
