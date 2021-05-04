<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class oliverde8ComfyExtension extends Extension
{

    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('scope-provider.yml');
        $loader->load('services.yml');
        $loader->load('form-type-provider.yml');
        // $loader->load('dummy-configs.yml');
    }
}
