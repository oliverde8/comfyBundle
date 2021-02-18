<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
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
     * Check if for a certain scope the value is inherited or if it's setted at that particular scope.
     *
     * @param string $configPath
     * @param string $scope
     * @return bool
     */
    public function doesInhertit(string $configPath, string $scope = null): bool;

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