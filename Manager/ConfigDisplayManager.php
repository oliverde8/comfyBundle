<?php

namespace oliverde8\ComfyBundle\Manager;

use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Model\Scope;
use oliverde8\ComfyBundle\Resolver\ScopeResolverInterface;
use oliverde8\ComfyBundle\Security\ScopeVoter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ConfigDisplayManager
{
    protected AuthorizationCheckerInterface $checker;
    protected ConfigManagerInterface $configManager;
    protected ScopeResolverInterface $scopeResolver;

    public function __construct(
        ConfigManagerInterface $configManager,
        ScopeResolverInterface $scopeResolver,
        AuthorizationCheckerInterface $checker
    ) {
        $this->configManager = $configManager;
        $this->scopeResolver = $scopeResolver;
        $this->checker = $checker;
    }

    public function getConfigHtmlName(ConfigInterface $config)
    {
        return str_replace("/", "-", $config->getPath());
    }

    public function getScopeTreeForHtml()
    {
        return $this->getRecursiveScopeTreeForHtml($this->scopeResolver->getScopeTree());
    }

    public function getFirstConfigPath(): ?string
    {
        return $this->getRecursiveFirstConfigPath($this->configManager->getAllConfigs()->getArray());
    }

    /**
     * Get path to the first config element.
     *
     * @param $configItems
     * @param string $parent
     * @return string|null
     * @deprecated This method is now internal, use getFirstConfigPath instead.
     */
    public function getRecursiveFirstConfigPath($configItems, $parent = "")
    {
        foreach ($configItems as $key => $config) {
            if (is_object($config)) {
                return null;
            }
            $child = $this->getRecursiveFirstConfigPath( $config, $parent . "/" . $key);

            if (is_null($child)) {
                return $parent . "/" . $key;
            } else {
                return $child;
            }
        }
    }

    protected function getRecursiveScopeTreeForHtml($tree, $parent = "")
    {
        $data = [];

        if (isset($tree['~name'])) {
            $data['name'] = $tree['~name'];
            unset($tree['~name']);
        }

        $data['sub_scopes'] = [];
        if (count($tree) > 0) {
            foreach ($tree as $key => $item) {
                if ($this->checker->isGranted(ScopeVoter::ACTION_VIEW, new Scope($parent . $key . "/"))) {
                    $data['sub_scopes'][$parent . $key] = $this->getRecursiveScopeTreeForHtml($item, $parent . $key . "/");
                }
            }
        }

        return $data;
    }


}
