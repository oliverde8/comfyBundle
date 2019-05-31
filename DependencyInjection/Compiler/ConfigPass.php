<?php

namespace oliverde8\ComfyBundle\DependencyInjection\Compiler;

use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * PluginPass to register all plugins to the plugin manager.
 *
 * @package eXpansion\Framework\Core\DependencyInjection\Compiler
 */
class ConfigPass implements CompilerPassInterface
{
    /**
     * Register all data providers to the Manager.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ConfigManagerInterface::class)) {
            return;
        }
        $definition = $container->getDefinition(ConfigManagerInterface::class);

        // Find all config's
        $configs = $container->findTaggedServiceIds('comfy.config');
        foreach ($configs as $id => $tags) {
            $definition->addMethodCall(
                'registerConfig',
                [new Reference($id)]
            );
        }
    }
}
