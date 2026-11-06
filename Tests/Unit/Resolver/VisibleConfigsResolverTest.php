<?php

declare(strict_types=1);

namespace oliverde8\ComfyBundle\Tests\Unit\Resolver;

use oliverde8\ComfyBundle\Manager\ConfigManager;
use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Model\TextConfig;
use oliverde8\ComfyBundle\Resolver\SimpleScopeResolver;
use oliverde8\ComfyBundle\Resolver\VisibleConfigsResolver;
use oliverde8\ComfyBundle\Tests\Fixtures\InMemoryStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validation;

class VisibleConfigsResolverTest extends TestCase
{
    private function resolver(array $denied = []): VisibleConfigsResolver
    {
        $manager = new ConfigManager(new SimpleScopeResolver('default', ['default' => 'Default']), new InMemoryStorage());
        $validator = Validation::createValidator();
        $manager->registerConfig(new TextConfig($manager, $validator, 'general/site/name', 'Name'));
        $manager->registerConfig(new TextConfig($manager, $validator, 'general/site/url', 'Url'));
        $manager->registerConfig(new TextConfig($manager, $validator, 'general/contact/mail', 'Mail'));
        $manager->registerConfig(new TextConfig($manager, $validator, 'api/token', 'Token', isHidden: true));

        $checker = $this->createMock(AuthorizationCheckerInterface::class);
        $checker->method('isGranted')->willReturnCallback(
            fn (string $action, ConfigInterface $config) => !in_array($config->getPath(), $denied, true)
        );

        return new VisibleConfigsResolver($checker, $manager);
    }

    public function testGetAllAllowedConfigsSkipsHiddenAndDenied(): void
    {
        $tree = $this->resolver(['general/contact/mail'])->getAllAllowedConfigs();

        $this->assertSame(['general'], array_keys($tree));
        $this->assertSame(['site'], array_keys($tree['general']));
        $this->assertSame(['name', 'url'], array_keys($tree['general']['site']));
    }

    public function testGetAllowedConfigs(): void
    {
        $configs = $this->resolver(['general/site/url'])->getAllowedConfigs('general/site');

        $this->assertSame(['general/site/name'], array_map(fn (ConfigInterface $config) => $config->getPath(), $configs));
    }
}
