<?php

namespace Starkerxp\CampagneBundle;

use Starkerxp\CampagneBundle\Api\ApiPass;
use Starkerxp\CampagneBundle\Render\RenderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class StarkerxpCampagneBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RenderPass());
        $container->addCompilerPass(new ApiPass());
    }
}
