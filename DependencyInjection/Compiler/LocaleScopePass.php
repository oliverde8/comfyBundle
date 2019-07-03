<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\DependencyInjection\Compiler;


use oliverde8\ComfyBundle\Resolver\ScopeResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Intl\Intl;

class LocaleScopePass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        // TODO Need to check if this is indeed the locale provider to use.
        $scopes = ["default" => "scope.default"];

        foreach (Intl::getLocaleBundle()->getLocaleNames() as $code => $name) {
            $code = str_replace("_", "/", $code);
            $scopes["default/$code"] = $name;
        }

        $definition = $container->getDefinition('oliverde8.comfy_bundle.scope_resolver.locales');
        $definition->setArgument('$scopes', $scopes);
        $definition->setArgument('$defaultScope', "default");

    }
}