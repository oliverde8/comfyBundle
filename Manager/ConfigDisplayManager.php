<?php

namespace oliverde8\ComfyBundle\Manager;

use oliverde8\ComfyBundle\Model\ConfigInterface;
use oliverde8\ComfyBundle\Resolver\ScopeResolverInterface;

class ConfigDisplayManager
{
    protected ConfigManagerInterface $configManager;
    protected ScopeResolverInterface $scopeResolver;

    /**
     * ConfigDisplayManager constructor.
     *
     * @param ConfigManagerInterface $configManager
     * @param ScopeResolverInterface $scopeResolver
     */
    public function __construct(ConfigManagerInterface $configManager, ScopeResolverInterface $scopeResolver)
    {
        $this->configManager = $configManager;
        $this->scopeResolver = $scopeResolver;
    }

    public function getConfigHtmlName(ConfigInterface $config)
    {
        return str_replace("/", "-", $config->getPath());
    }

    public function getScopeTreeForHtml()
    {
        return $this->getRecursiveScopeTreeForHtml($this->scopeResolver->getScopeTree());
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
                $data['sub_scopes'][$parent . $key] = $this->getRecursiveScopeTreeForHtml($item, $parent . $key . "/");
            }
        }

        return $data;
    }

}