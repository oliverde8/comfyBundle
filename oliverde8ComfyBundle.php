<?php
/**
 * @author    Wide Agency Dev Team
 * @category  kit-v2-symfony
 */

namespace oliverde8\ComfyBundle;

use oliverde8\ComfyBundle\DependencyInjection\Compiler\ConfigPass;
use oliverde8\ComfyBundle\DependencyInjection\Compiler\LocaleScopePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class oliverde8ComfyBundle extends Bundle
{

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConfigPass());
        $container->addCompilerPass(new LocaleScopePass());
    }
}