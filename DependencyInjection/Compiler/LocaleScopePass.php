<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Intl\Exception\MissingResourceException;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Intl\Locales;

class LocaleScopePass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container): void
    {
        // TODO Need to check if this is indeed the locale provider to use.
        $scopes = [
            "default" => "Default",
        ];

        $locales = $container->hasParameter('kernel.enabled_locales') ? $container->getParameter('kernel.enabled_locales') : [];
        if (empty($locales)) {
            $locales = Locales::getLocales();
        }

        foreach ($locales as $locale) {
            $parts = explode("_", $locale);
            for ($i = 1; $i <= count($parts); $i++) {
                $code = implode("_", array_slice($parts, 0, $i));
                $scopeKey = "default/" . str_replace("_", "/", $code);
                if (isset($scopes[$scopeKey])) {
                    continue;
                }

                try {
                    $scopes[$scopeKey] = Locales::getName($code);
                } catch (MissingResourceException) {
                    $scopes[$scopeKey] = $code;
                }
            }
        }

        $definition = $container->getDefinition('oliverde8.comfy_bundle.scope_resolver.locales');
        $definition->setArgument('$scopes', $scopes);
        $definition->setArgument('$defaultScope', "default");
    }
}