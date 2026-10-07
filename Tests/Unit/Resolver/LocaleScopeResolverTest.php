<?php

declare(strict_types=1);

namespace oliverde8\ComfyBundle\Tests\Unit\Resolver;

use oliverde8\ComfyBundle\DependencyInjection\Compiler\LocaleScopePass;
use oliverde8\ComfyBundle\Resolver\LocaleScopeResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleScopeResolverTest extends TestCase
{
    private function scopesFromPass(): array
    {
        $container = new ContainerBuilder();
        $container->setDefinition('oliverde8.comfy_bundle.scope_resolver.locales', new Definition(LocaleScopeResolver::class));
        (new LocaleScopePass())->process($container);

        return $container->getDefinition('oliverde8.comfy_bundle.scope_resolver.locales')->getArgument('$scopes');
    }

    private function resolver(?string $locale): LocaleScopeResolver
    {
        $stack = new RequestStack();
        if (!is_null($locale)) {
            $request = new Request();
            $request->setLocale($locale);
            $stack->push($request);
        }

        return new LocaleScopeResolver('default', $this->scopesFromPass(), $stack);
    }

    public function testNoRequestUsesDefaultScope(): void
    {
        $resolver = $this->resolver(null);

        $this->assertSame('default', $resolver->getCurrentScope());
        $this->assertTrue($resolver->validateScope(null));
    }

    public function testLanguageLocale(): void
    {
        $resolver = $this->resolver('fr');

        $this->assertSame('default/fr', $resolver->getCurrentScope());
        $this->assertTrue($resolver->validateScope(null));
        $this->assertSame('default', $resolver->inherits(null));
    }

    public function testRegionLocaleIsAValidScope(): void
    {
        $resolver = $this->resolver('fr_FR');

        $this->assertTrue($resolver->validateScope(null));
        $this->assertSame('default/fr', $resolver->inherits(null));
    }

    public function testPassBuildsLeveledScopes(): void
    {
        $scopes = $this->scopesFromPass();

        $this->assertArrayHasKey('default', $scopes);
        $this->assertArrayHasKey('default/fr', $scopes);
        $this->assertArrayHasKey('default/fr/FR', $scopes);
        $this->assertArrayNotHasKey('default/fr_FR', $scopes);
    }
}
