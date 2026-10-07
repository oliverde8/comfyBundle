<?php

declare(strict_types=1);

namespace oliverde8\ComfyBundle\Tests\Unit\Manager;

use oliverde8\ComfyBundle\Exception\UnknownConfigPathException;
use oliverde8\ComfyBundle\Exception\UnknownScopeException;
use oliverde8\ComfyBundle\Manager\ConfigManager;
use oliverde8\ComfyBundle\Model\TextConfig;
use oliverde8\ComfyBundle\Resolver\LocaleScopeResolver;
use oliverde8\ComfyBundle\Resolver\SimpleScopeResolver;
use oliverde8\ComfyBundle\Tests\Fixtures\InMemoryStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validation;

class ConfigManagerTest extends TestCase
{
    private const SCOPES = [
        'default' => 'Default',
        'default/fr' => 'French',
        'default/fr/FR' => 'French (France)',
        'default/en' => 'English',
    ];

    private function manager(InMemoryStorage $storage, ?SimpleScopeResolver $resolver = null): ConfigManager
    {
        $manager = new ConfigManager($resolver ?? new SimpleScopeResolver('default', self::SCOPES), $storage);
        $validator = Validation::createValidator();
        $manager->registerConfig(new TextConfig($manager, $validator, 'site/name', 'Name', defaultValue: 'Default name'));
        $manager->registerConfig(new TextConfig($manager, $validator, 'site/url', 'Url'));

        return $manager;
    }

    public function testGetReturnsDefaultValue(): void
    {
        $manager = $this->manager(new InMemoryStorage());

        $this->assertSame('Default name', $manager->get('site/name'));
        $this->assertSame('Default name', $manager->get('site/name', 'default/fr/FR'));
        $this->assertNull($manager->get('site/url'));
        $this->assertTrue($manager->doesInhertit('site/name'));
    }

    public function testGetInheritsStoredParentValues(): void
    {
        $manager = $this->manager(new InMemoryStorage([
            'default' => ['site/name' => 'Root'],
            'default/fr' => ['site/name' => 'Racine'],
        ]));

        $this->assertSame('Root', $manager->get('site/name', 'default'));
        $this->assertSame('Racine', $manager->get('site/name', 'default/fr'));
        $this->assertSame('Racine', $manager->get('site/name', 'default/fr/FR'));
        $this->assertSame('Root', $manager->get('site/name', 'default/en'));

        $this->assertFalse($manager->doesInhertit('site/name', 'default/fr'));
        $this->assertTrue($manager->doesInhertit('site/name', 'default/fr/FR'));
    }

    public function testSetStoresValue(): void
    {
        $storage = new InMemoryStorage();
        $manager = $this->manager($storage);

        $manager->set('site/name', 'Nom', 'default/fr');

        $this->assertSame('Nom', $manager->get('site/name', 'default/fr'));
        $this->assertFalse($manager->doesInhertit('site/name', 'default/fr'));
        $this->assertSame(['default/fr' => ['site/name' => 'Nom']], $storage->values);
    }

    public function testSetOnParentPropagatesToLoadedChildren(): void
    {
        $manager = $this->manager(new InMemoryStorage());
        $this->assertSame('Default name', $manager->get('site/name', 'default/fr/FR'));

        $manager->set('site/name', 'Nom', 'default/fr');

        $this->assertSame('Nom', $manager->get('site/name', 'default/fr/FR'));
    }

    public function testSetNullRestoresParentValue(): void
    {
        $storage = new InMemoryStorage([
            'default' => ['site/name' => 'Root'],
            'default/fr' => ['site/name' => 'Racine'],
        ]);
        $manager = $this->manager($storage);

        $manager->set('site/name', null, 'default/fr');

        $this->assertSame('Root', $manager->get('site/name', 'default/fr'));
        $this->assertTrue($manager->doesInhertit('site/name', 'default/fr'));
        $this->assertSame(['default' => ['site/name' => 'Root']], $storage->values);
    }

    public function testSetNullOnRootScopeRestoresDefaultValue(): void
    {
        $manager = $this->manager(
            new InMemoryStorage(['default' => ['site/name' => 'Root']]),
            new SimpleScopeResolver('default/fr', self::SCOPES)
        );
        $manager->set('site/name', 'Racine', 'default/fr');

        $manager->set('site/name', null, 'default');

        $this->assertSame('Default name', $manager->get('site/name', 'default'));
    }

    public function testScopeIsLoadedOnce(): void
    {
        $storage = new InMemoryStorage();
        $manager = $this->manager($storage);

        $manager->get('site/name', 'default/fr');
        $manager->get('site/url', 'default/fr');

        $this->assertSame(1, $storage->loadCount);
    }

    public function testCurrentScopeFollowsRequestLocale(): void
    {
        $stack = new RequestStack();
        $manager = $this->manager(
            new InMemoryStorage(['default/fr' => ['site/name' => 'Nom'], 'default/en' => ['site/name' => 'Name']]),
            new LocaleScopeResolver('default', self::SCOPES, $stack)
        );

        $fr = new Request();
        $fr->setLocale('fr');
        $stack->push($fr);
        $this->assertSame('Nom', $manager->get('site/name'));
        $stack->pop();

        $en = new Request();
        $en->setLocale('en');
        $stack->push($en);
        $this->assertSame('Name', $manager->get('site/name'));
    }

    public function testUnknownPathThrows(): void
    {
        $this->expectException(UnknownConfigPathException::class);
        $this->manager(new InMemoryStorage())->get('unknown/path');
    }

    public function testUnknownScopeThrows(): void
    {
        $this->expectException(UnknownScopeException::class);
        $this->manager(new InMemoryStorage())->get('site/name', 'default/de');
    }

    public function testGetAllConfigsIsATree(): void
    {
        $tree = $this->manager(new InMemoryStorage())->getAllConfigs()->getArray();

        $this->assertSame(['name', 'url'], array_keys($tree['site']));
        $this->assertInstanceOf(TextConfig::class, $tree['site']['name']);
    }
}
