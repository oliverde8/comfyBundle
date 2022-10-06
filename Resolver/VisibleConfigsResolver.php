<?php

namespace oliverde8\ComfyBundle\Resolver;

use oliverde8\AssociativeArraySimplified\AssociativeArray;
use oliverde8\ComfyBundle\Manager\ConfigManagerInterface;
use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Security\ConfigVoter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class VisibleConfigsResolver
{
    protected AuthorizationCheckerInterface $checker;

    protected ConfigManagerInterface $configManager;

    /**
     * @param AuthorizationCheckerInterface $checker
     * @param ConfigManagerInterface $configManager
     */
    public function __construct(AuthorizationCheckerInterface $checker, ConfigManagerInterface $configManager)
    {
        $this->checker = $checker;
        $this->configManager = $configManager;
    }

    public function getAllowedConfigs(string $configPath, string $action = ConfigVoter::ACTION_VIEW): array
    {
        $allowedConfigs = [];
        foreach ($this->getAllowedConfigsGenerator($configPath, $action) as $config) {
            $allowedConfigs[] = $config;
        }

        return $allowedConfigs;
    }

    public function getAllAllowedConfigs(string $action = ConfigVoter::ACTION_VIEW): array
    {
        $allowedConfigs = new AssociativeArray();

        $allConfigs = $this->configManager->getAllConfigs();
        array_walk_recursive($allConfigs, function ($config, $key) use($allowedConfigs, $action) {
            if (is_object($config)) {
                if ($config->isHidden()) {
                    return;
                }

                if ($this->checker->isGranted($action, $config)) {
                    $allowedConfigs->set($config->getPath(), $config);
                }
            }
        });

        return $allowedConfigs->getArray();
    }

    /**
     * @return \Generator|ConfigInterface[]
     * @throws \oliverde8\ComfyBundle\Exception\UnknownScopeException
     */
    protected function getAllowedConfigsGenerator(string $configPath, string $action)
    {
        $configs = $this->configManager->getAllConfigs()->get($configPath, []);
        if (empty($configPath)) {
            $this->configManager->getAllConfigs();
        }

        foreach ($this->configManager->getAllConfigs()->get($configPath, []) as $config) {
            if ($config->isHidden()) {
                continue;
            }

            if ($this->checker->isGranted($action, $config)) {
               yield $config;
            }
        }
    }
}
