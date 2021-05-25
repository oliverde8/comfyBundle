<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\Resolver;

use oliverde8\AssociativeArraySimplified\AssociativeArray;

class SimpleScopeResolver extends AbstractScopeResolver implements ScopeResolverInterface
{
    /** @var string */
    protected string $defaultScope;

    /** @var array */
    protected array $scopes;

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
     * @inheritDoc
     */
    public function getCurrentScope(): string
    {
        return $this->defaultScope;
    }

    /**
     * @inheritDoc
     */
    protected function initScopes(): array
    {
        return $this->scopes;
    }
}
