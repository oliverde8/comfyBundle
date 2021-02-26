<?php


namespace oliverde8\ComfyBundle\DependencyInjection\Compiler;

use oliverde8\ComfyBundle\Form\Type\ConfigsForm;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormTypePass implements CompilerPassInterface
{
    /**
     * Register all data providers to the Manager.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ConfigsForm::class)) {
            return;
        }
        $definition = $container->getDefinition(ConfigsForm::class);

        // Find all config's
        $providerDefinitions = $container->findTaggedServiceIds('comfy.form_provider');
        $providerReferences = [];

        foreach ($providerDefinitions as $id => $tags) {
            $providerReferences[] = new Reference($id);
        }

        $definition->setArgument('$formTypeProviders', $providerReferences);
    }
}