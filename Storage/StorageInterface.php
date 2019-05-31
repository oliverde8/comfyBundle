<?php
/**
 * @author    Wide Agency Dev Team
 * @category  kit-v2-symfony
 */

namespace oliverde8\ComfyBundle\Storage;

interface StorageInterface
{
    public function save(string $configPath, string $scope, string $value);

    /**
     * @param array $scopes
     *
     * @return string[][]
     */
    public function load(array $scopes): array;
}