<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 28/09/2016
 * Time: 00:55.
 */

namespace Starkerxp\CampaignBundle\Render;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class RenderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definitionId = 'starkerxp_campaign.manager.render';
        if (!($container->has($definitionId))) {
            return false;
        }
        $definition = $container->findDefinition($definitionId);
        foreach (array_keys($container->findTaggedServiceIds('starkerxp_campaign.render')) as $id) {
            $definition->addMethodCall('addRenderService', [new Reference($id)]);
        }
    }
}
