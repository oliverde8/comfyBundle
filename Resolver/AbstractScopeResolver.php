<?php


namespace oliverde8\ComfyBundle\Resolver;


use oliverde8\AssociativeArraySimplified\AssociativeArray;

abstract class AbstractScopeResolver implements ScopeResolverInterface
{
    /** @var array */
    private ?array $scopes = null;

    /**
     * @inheritDoc
     */
    public function validateScope(string $scope = null): bool
    {
        $this->initScopes();

        $scopes = $this->getScopes();
        $scope = $this->getScope($scope);
        return isset($scopes[$scope]);
    }

    /**
     * @inheritDoc
     */
    public function getScope(string $scope = null): string
    {
        $this->initScopes();

        if (is_null($scope)) {
            $scope = $this->getCurrentScope();
        }

        return $scope;
    }

    /**
     * @inheritDoc
     */
    public function inherits(string $scope = null): ?string
    {
        $this->initScopes();
        $scope = $this->getScope($scope);

        $parts = explode('/', $scope);
        if (count($parts) <= 1) {
            return null;
        }

        array_pop($parts);
        return implode('/', $parts);
    }

    /**
     * @inheritDoc
     */
    public function getScopeTree() : array
    {
        $this->initScopes();

        $data = [];
        foreach ($this->scopes as $scopeKey => $scopeName) {
            $parentScopeKey = $this->inherits($scopeKey);
            if ($parentScopeKey && !AssociativeArray::checkKeyExist($data, $parentScopeKey . "/~name")) {
                AssociativeArray::setFromKey($data, $parentScopeKey . "/~name", $parentScopeKey);
            }

            AssociativeArray::setFromKey($data, $scopeKey . "/~name", $scopeName);
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getScopes(): array
    {
        if (is_null($this->scopes)) {
            $this->scopes = $this->initScopes();
        }

        return $this->scopes;
    }

    abstract protected function initScopes(): array;
}
