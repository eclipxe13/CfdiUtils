<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodeQuality\Rector\Concat\JoinStringConcatRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\Config\RectorConfig;
use Rector\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        ThisCallOnStaticMethodToStaticCallRector::class,
        CombinedAssignRector::class,
        ExplicitBoolCompareRector::class,
        JoinStringConcatRector::class,
        SimplifyIfElseToTernaryRector::class,
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php74: true)
    ->withTypeCoverageLevel(200)
//    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(30)
;
