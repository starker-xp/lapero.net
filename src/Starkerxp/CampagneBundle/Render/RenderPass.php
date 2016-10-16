<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 28/09/2016
 * Time: 00:55.
 */

namespace Starkerxp\CampagneBundle\Render;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class RenderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('starkerxp_campagne.manager.render');
        foreach (array_keys($container->findTaggedServiceIds('starkerxp_campagne.render')) as $id) {
            $definition->addMethodCall('addRenderService', [new Reference($id)]);
        }
    }
}
