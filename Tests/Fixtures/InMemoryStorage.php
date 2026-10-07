<?php

declare(strict_types=1);

namespace oliverde8\ComfyBundle\Tests\Fixtures;

use oliverde8\ComfyBundle\Storage\StorageInterface;

class InMemoryStorage implements StorageInterface
{
    public array $values = [];
    public int $loadCount = 0;

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    public function save(string $configPath, string $scope, ?string $value)
    {
        if (is_null($value)) {
            unset($this->values[$scope][$configPath]);
            $this->values = array_filter($this->values);
            return;
        }
        $this->values[$scope][$configPath] = $value;
    }

    public function load(array $scopes): array
    {
        $this->loadCount++;
        return array_intersect_key($this->values, array_flip($scopes));
    }
}
