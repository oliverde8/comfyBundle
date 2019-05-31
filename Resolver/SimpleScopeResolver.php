<?php
/**
 * @author    Wide Agency Dev Team
 * @category  kit-v2-symfony
 */

namespace oliverde8\ComfyBundle\Resolver;

use oliverde8\AssociativeArraySimplified\AssociativeArray;

class SimpleScopeResolver implements ScopeResolverInterface
{
    /** @var string */
    protected $defaultScope;

    /** @var array */
    protected $scopes;

    /**
     * SimpleScopeResolver constructor.
     *
     * @param string $defaultScope
     * @param array $scopes
     */
    public function __construct(string $defaultScope, array $scopes)
    {
        $this->defaultScope = $defaultScope;
        $this->scopes = $scopes;
    }


    /**
     * Get current scope.
     *
     * @return string
     */
    public function getCurrentScope(): string
    {
        return $this->defaultScope;
    }

    /**
     * Check if a scope is valid or not.
     *
     * @param string|null $scope The path of the scope; null will fallback to default scope.
     *
     * @return bool
     */
    public function validateScope(string $scope = null): bool
    {
        $scope = $this->getScope($scope);
        return isset($this->scopes[$scope]);
    }

    /**
     * Get scope.
     *
     * @param string|null $scope
     *
     * @return string
     */
    public function getScope(string $scope = null): string
    {
        if (is_null($scope)) {
            $scope = $this->getCurrentScope();
        }

        return $scope;
    }

    /**
     * Get scope this scope inherits from. Retursn null if scope don't inherit from any other scope.
     *
     * @param string|null $scope
     *
     * @return string|null
     */
    public function inherits(string $scope = null): ?string
    {
        $scope = $this->getScope($scope);

        $parts = explode('/', $scope);
        if (count($parts) <= 1) {
            return null;
        }

        return $parts[count($parts) - 1];
    }

    public function getScopeTree() : array
    {
        $data = [];

        foreach ($this->scopes as $scopeKey => $scopeName) {
            AssociativeArray::setFromKey($data, $scopeKey . "/~name", $scopeName);
        }

        return $data;
    }
}