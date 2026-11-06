<?php

declare(strict_types=1);

namespace oliverde8\ComfyBundle\Tests\Unit\DependencyInjection;

use oliverde8\ComfyBundle\DependencyInjection\Compiler\LocaleScopePass;
use oliverde8\ComfyBundle\Resolver\LocaleScopeResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class LocaleScopePassTest extends TestCase
{
    private function scopes(?array $enabledLocales): array
    {
        $container = new ContainerBuilder();
        if (!is_null($enabledLocales)) {
            $container->setParameter('kernel.enabled_locales', $enabledLocales);
        }
        $container->setDefinition('oliverde8.comfy_bundle.scope_resolver.locales', new Definition(LocaleScopeResolver::class));
        (new LocaleScopePass())->process($container);

        return $container->getDefinition('oliverde8.comfy_bundle.scope_resolver.locales')->getArgument('$scopes');
    }

    public function testUsesEnabledLocalesAndAddsParents(): void
    {
        $this->assertSame(
            [
                'default' => 'Default',
                'default/en' => 'English',
                'default/fr' => 'French',
                'default/fr/FR' => 'French (France)',
                'default/de' => 'German',
                'default/de/CH' => 'German (Switzerland)',
            ],
            $this->scopes(['en', 'fr', 'fr_FR', 'de_CH'])
        );
    }

    public function testFallsBackToAllLocales(): void
    {
        foreach ([null, []] as $enabledLocales) {
            $scopes = $this->scopes($enabledLocales);

            $this->assertSame('Default', $scopes['default']);
            $this->assertArrayHasKey('default/fr/FR', $scopes);
            $this->assertArrayHasKey('default/ja', $scopes);
        }
    }
}
