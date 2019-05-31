<?php
/**
 * @author    Wide Agency Dev Team
 * @category  kit-v2-symfony
 */

namespace oliverde8\ComfyBundle\Manager;


use oliverde8\AssociativeArraySimplified\AssociativeArray;
use oliverde8\ComfyBundle\Exception\UnknownConfigPathException;
use oliverde8\ComfyBundle\Exception\UnknownScopeException;
use oliverde8\ComfyBundle\Model\ConfigInterface;

interface ConfigManagerInterface
{
    /**
     * Persist a configuration value.
     *
     * @param string $configPath Path to the configuration.
     * @param string|null $value New normalized value,
     * @param string|null $scope Path to scope, or null to use current scope.
     *
     * @return mixed
     *
     * @throws UnknownConfigPathException When config path is not known.
     * @throws UnknownScopeException When config path is not known.
     */
    public function set(string $configPath, ?string $value, string $scope = null): self;

    /**
     * Get a certain config value.
     *
     * @param string $configPath Path to the configuration.
     * @param string|null $scope Path to scope or null to use current scope.
     *
     * @return mixed
     *
     * @throws UnknownConfigPathException When config path is not known.
     * @throws UnknownScopeException When config path is not known.
     */
    public function get(string $configPath, string $scope = null): string;

    /**
     * Get all config values of a certain scope.
     *
     * @param string $scope Path to scope, or null to use current scope.
     *
     * @return AssociativeArray
     *
     * @throws UnknownScopeException When config path is not known.
     */
    public function getAllConfigs(string $scope = null) : AssociativeArray;

    /**
     * Register a config to be handled by the config manager.
     *
     * @param ConfigInterface $config
     * @internal
     */
    public function registerConfig(ConfigInterface $config);
}