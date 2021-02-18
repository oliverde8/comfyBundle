<?php

namespace oliverde8\ComfyBundle\DependencyInjection\Compiler;

use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use oliverde8\ComfyBundle\Model\ConfigInterface;
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
            $definition->addMethodCall('registerConfig', [new Reference($id)]);

            $variableName = $this->normalizeIdForVariable($id);
            $container->registerAliasForArgument($id, ConfigInterface::class, $variableName);
        }
    }

    /**
     * Normalize the id to make a variable name out of it.
     *
     * @param string $id
     * @return string
     */
    protected function normalizeIdForVariable(string $id): string
    {
        $idParts = explode(".", $id);

        // Remove vendor name.
        array_shift($idParts);

        $variableName = '';
        foreach ($idParts as $part) {
            if (strtolower($part) !== 'comfy') {
                $part = str_replace("_", " ", $part);
                $part = ucwords($part);
                $part = str_replace(" ", "", $part);

                $variableName .= ucfirst($part);
            }
        }

        return lcfirst($variableName);
    }
}
