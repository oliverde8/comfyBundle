<?php

declare(strict_types=1);

namespace oliverde8\ComfyBundle\Tests\Unit\Resolver;

use oliverde8\ComfyBundle\Resolver\SimpleScopeResolver;
use PHPUnit\Framework\TestCase;

class SimpleScopeResolverTest extends TestCase
{
    private function resolver(): SimpleScopeResolver
    {
        return new SimpleScopeResolver('default', [
            'default' => 'Default',
            'default/fr' => 'French',
            'default/fr/FR' => 'French (France)',
            'default/en' => 'English',
        ]);
    }

    public function testValidateScopeOnFreshResolver(): void
    {
        $resolver = $this->resolver();

        $this->assertTrue($resolver->validateScope('default/fr'));
        $this->assertTrue($resolver->validateScope('default/fr/FR'));
        $this->assertFalse($resolver->validateScope('default/de'));
        $this->assertFalse($resolver->validateScope('unknown'));
    }

    public function testValidateScopeNullUsesCurrentScope(): void
    {
        $this->assertTrue($this->resolver()->validateScope(null));
        $this->assertFalse((new SimpleScopeResolver('missing', ['default' => 'Default']))->validateScope(null));
    }

    public function testGetScope(): void
    {
        $resolver = $this->resolver();

        $this->assertSame('default', $resolver->getScope(null));
        $this->assertSame('default/fr', $resolver->getScope('default/fr'));
        $this->assertSame('default', $resolver->getCurrentScope());
    }

    public function testInherits(): void
    {
        $resolver = $this->resolver();

        $this->assertSame('default/fr', $resolver->inherits('default/fr/FR'));
        $this->assertSame('default', $resolver->inherits('default/fr'));
        $this->assertNull($resolver->inherits('default'));
        $this->assertNull($resolver->inherits(null));
    }

    public function testGetScopeTree(): void
    {
        $this->assertSame(
            [
                'default' => [
                    '~name' => 'Default',
                    'fr' => [
                        '~name' => 'French',
                        'FR' => ['~name' => 'French (France)'],
                    ],
                    'en' => ['~name' => 'English'],
                ],
            ],
            $this->resolver()->getScopeTree()
        );
    }

    public function testGetScopeTreeAddsMissingParents(): void
    {
        $resolver = new SimpleScopeResolver('default', ['default/fr/FR' => 'French (France)']);

        $this->assertSame(
            [
                'default' => [
                    'fr' => [
                        '~name' => 'default/fr',
                        'FR' => ['~name' => 'French (France)'],
                    ],
                ],
            ],
            $resolver->getScopeTree()
        );
    }
}
