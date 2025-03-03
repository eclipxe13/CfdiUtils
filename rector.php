<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodeQuality\Rector\Concat\JoinStringConcatRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector;
use Rector\Config\RectorConfig;
use Rector\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/development',
    ])
    ->withSkip([
        ThisCallOnStaticMethodToStaticCallRector::class,
        CombinedAssignRector::class,
        ExplicitBoolCompareRector::class,
        JoinStringConcatRector::class,
        SimplifyIfElseToTernaryRector::class,
        IssetOnPropertyObjectToPropertyExistsRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php80: true)
    ->withTypeCoverageLevel(200)
    ->withDeadCodeLevel(200)
    ->withCodeQualityLevel(200)
;
