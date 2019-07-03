<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  @category  oliverde8/ComfyBundle
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