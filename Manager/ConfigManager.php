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
use oliverde8\ComfyBundle\Resolver\ScopeResolverInterface;
use oliverde8\ComfyBundle\Storage\StorageInterface;

class ConfigManager implements ConfigManagerInterface
{
    /** @var ScopeResolverInterface */
    protected $scopeResolver;

    /** @var StorageInterface */
    protected $storage;

    /** @var AssociativeArray */
    protected $configTree;

    /** @var ConfigInterface[] */
    protected $configs;

    /** @var array */
    protected $configValues;

    /** @var array */
    protected $configParentInheritance;

    protected array $resolvedScopes = [];

    /**
     * ConfigManager constructor.
     *
     * @param ScopeResolverInterface $scopeResolver
     * @param StorageInterface $storage
     */
    public function __construct(ScopeResolverInterface $scopeResolver, StorageInterface $storage)
    {
        $this->scopeResolver = $scopeResolver;
        $this->storage = $storage;
        // TODO make it configurable.
        $this->configTree = new AssociativeArray();
    }

    /**
     * @inheritDoc
     */
    public function set(string $configPath, ?string $value, string $scope = null): ConfigManagerInterface
    {
        $this->validatePath($configPath);
        $scope = $this->validateScope($scope);

        if (!is_null($value)) {
            $this->configValues[$scope][$configPath] = $value;
            $this->configParentInheritance[$scope][$configPath] = false;
        } else {
            $this->scopeResolver->inherits($scope);
            $previousScope = $this->scopeResolver->getScope($this->scopeResolver->inherits($scope));
            $this->configValues[$scope][$configPath] = $this->configValues[$previousScope][$configPath];
            $this->configParentInheritance[$scope][$configPath] = true;
        }

        $this->storage->save($configPath, $scope, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $configPath, string $scope = null): ?string
    {
        $this->validatePath($configPath);
        $scope = $this->validateScope($scope);

        return $this->configValues[$scope][$configPath];
    }

    /**
     * @inheritDoc
     */
    public function doesInhertit(string $configPath, string $scope = null): bool
    {
        $this->validatePath($configPath);
        $scope = $this->validateScope($scope);
        return $this->configParentInheritance[$scope][$configPath];
    }

    /**
     * @inheritDoc
     */
    public function getAllConfigs(string $scope = null): AssociativeArray
    {
        return $this->configTree;
    }

    /**
     * @inheritDoc
     */
    public function registerConfig(ConfigInterface $config)
    {
        $this->configTree->set($config->getPath(), $config);
        $this->configs[$config->getPath()] = $config;
    }


    protected function validateScope($scope)
    {
        if (isset($this->resolvedScopes[$scope])) {
            return $this->resolvedScopes[$scope];
        }

        if (!$this->scopeResolver->validateScope($scope)) {
            throw new UnknownScopeException("Scope '$scope' was not found!");
        }

        $scopeValue = $this->scopeResolver->getScope($scope);
        $this->resolvedScopes[$scope] = $scopeValue;

        if (!isset($this->configValues[$scopeValue])) {
            // TODO first check in cache.
            $this->loadScopeFromStorage($scopeValue);
        }

        return $scopeValue;
    }

    protected function validatePath($path)
    {
        if (!isset($this->configs[$path])) {
            throw new UnknownConfigPathException("No configuration with path: '$path' has been registered");
        }
    }

    protected function loadScopeFromStorage($scope)
    {
        $scopes = [];

        $lastScope = $scope;
        do {
            $scopes[] = $lastScope;
            $lastScope = $this->scopeResolver->inherits($lastScope);
        } while(!is_null($lastScope));

        $values = $this->storage->load($scopes);
        $previousScope = null;

        foreach (array_reverse($scopes) as $scope) {
            foreach ($this->configs as $config) {
                if (is_null($previousScope)) {
                    $previousValue = $config->getDefaultValue();
                } else {
                    $previousValue = $this->configValues[$previousScope][$config->getPath()];
                }

                $this->configParentInheritance[$scope][$config->getPath()] = !AssociativeArray::checkKeyExist($values, [$scope, $config->getPath()]);
                $this->configValues[$scope][$config->getPath()] = AssociativeArray::getFromKey($values, [$scope, $config->getPath()], $previousValue);
            }
            $previousScope = $scope;
        }
    }
}
