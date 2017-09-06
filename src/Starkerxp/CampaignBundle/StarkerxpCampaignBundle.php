<?php

namespace Starkerxp\CampaignBundle;

use Starkerxp\CampaignBundle\Api\ApiPass;
use Starkerxp\CampaignBundle\Render\RenderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class StarkerxpCampaignBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RenderPass());
        $container->addCompilerPass(new ApiPass());
    }
}
