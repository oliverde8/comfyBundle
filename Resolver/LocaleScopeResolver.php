<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
 */

namespace oliverde8\ComfyBundle\Resolver;


use Symfony\Component\HttpFoundation\RequestStack;

class LocaleScopeResolver extends SimpleScopeResolver
{
    /** @var RequestStack */
    protected $request;

    public function __construct(string $defaultScope, array $scopes, RequestStack $request)
    {
        parent::__construct($defaultScope, $scopes);
        $this->request = $request;
    }


    public function getCurrentScope(): string
    {
        return $this->defaultScope . "/" . $this->request->getCurrentRequest()->getLocale();
    }
}