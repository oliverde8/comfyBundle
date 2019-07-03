<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\Resolver;

interface ScopeResolverInterface
{
    /**
     * Get current scope.
     *
     * @return string
     */
    public function getCurrentScope(): string;

    /**
     * Check if a scope is valid or not.
     *
     * @param string|null $scope The path of the scope; null will fallback to default scope.
     *
     * @return bool
     */
    public function validateScope(string $scope = null): bool;

    /**
     * Get scope.
     *
     * @param string|null $scope
     *
     * @return string
     */
    public function getScope(string $scope = null): string;

    /**
     * Get scope this scope inherits from.
     *
     * @param string|null $scope
     *
     * @return string
     */
    public function inherits(string $scope = null): ?string;

    public function getScopeTree() : array;
}