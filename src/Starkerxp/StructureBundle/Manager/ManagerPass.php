<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 28/09/2016
 * Time: 00:55.
 */

namespace Starkerxp\StructureBundle\Manager;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('starkerxp_structure.manager.entity');
        foreach (array_keys($container->findTaggedServiceIds('starkerxp.manager.entity')) as $id) {
            $definition->addMethodCall('addService', [new Reference($id)]);
        }
    }
}
