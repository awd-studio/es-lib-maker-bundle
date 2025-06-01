<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return RectorConfig::configure()
    ->withCache(
        cacheClass: FileCacheStorage::class,
        cacheDirectory: __DIR__ . '/../cache/rector-tests'
    )
    ->withPaths([
        __DIR__ . '/../../tests',
    ])
    ->withPhpSets(php83: true)
//    ->withAttributesSets(phpunit: true)
    ->withRules([
        DeclareStrictTypesRector::class
    ])
;
