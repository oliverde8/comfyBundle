<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Command',
        __DIR__ . '/DependencyInjection',
        __DIR__ . '/Entity',
        __DIR__ . '/Exception',
        __DIR__ . '/Form',
        __DIR__ . '/Manager',
        __DIR__ . '/Model',
        __DIR__ . '/Resolver',
        __DIR__ . '/Security',
        __DIR__ . '/Storage',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
