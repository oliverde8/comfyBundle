<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\Resolver;

class SimpleScopeResolver extends AbstractScopeResolver implements ScopeResolverInterface
{
    public function __construct(
        protected string $defaultScope,
        protected array $scopes
    ) {
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
