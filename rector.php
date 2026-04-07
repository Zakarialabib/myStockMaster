<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Rector\MethodCall\WhereToWhereLikeRector;
use RectorLaravel\Rector\StaticCall\RouteActionCallableRector;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;
use RectorLaravel\Set\LaravelSetProvider;

/**
 * myStockMaster – Rector config (Rector 2.x + Laravel 12 + Livewire 4).
 *
 * You said your issues are mostly "property type issues":
 * - Rector core: enable type declarations via prepared sets (or type-coverage levels)
 * - rector-laravel: enable Laravel type declarations set (relations generics etc.)
 *
 * This config is intentionally broad, but still keeps the most dangerous churn behind
 * explicit sets (so you can comment out the ones you don't want).
 */
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app/Livewire',
        // __DIR__ . '/bootstrap',
        // __DIR__ . '/config',
        // __DIR__ . '/database',
        // __DIR__ . '/routes',
        // __DIR__ . '/tests',
    ])

    // Keep Rector from touching generated/third-party/artifacts.
    ->withSkip([
        __DIR__ . '/bootstrap/cache',
        __DIR__ . '/public',
        __DIR__ . '/storage',
        __DIR__ . '/vendor',
        __DIR__ . '/node_modules',
        __DIR__ . '/resources/views', // Blade is not a great target for Rector
        __DIR__ . '/lang',
    ])

    // Your composer.json says php ^8.3; Rector can read it automatically,
    // but we pin it here to avoid accidental downgrades in CI.
    ->withPhpVersion(PhpVersion::PHP_83)

    // Cache (recommended; see Rector docs)
    ->withCache(__DIR__ . '/storage/rector')

    // PHP upgrade sets based on composer.json (best practice in Rector docs).
    // If you want, you can pass named args like: ->withPhpSets(php83: true);
    ->withPhpSets()

    // Rector core sets (Rector 2.x recommended approach)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        naming: true,
        privatization: true,
        typeDeclarations: true,
    )

    // rector-laravel provides its own set provider (see driftingly/rector-laravel README).
    // This is the supported way to enable "composer-based Laravel upgrade sets".
    ->withSetProviders(LaravelSetProvider::class)
    ->withComposerBased(laravel: true)

    // Laravel 12 project: keep it at UP_TO_LARAVEL_120 (not LARAVEL_130).
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_120,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_IF_HELPERS,
        LaravelSetList::LARAVEL_TYPE_DECLARATIONS,
        LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,
        LaravelSetList::LARAVEL_ARRAYACCESS_TO_METHOD_CALL,
        LaravelSetList::LARAVEL_CONTAINER_STRING_TO_FULLY_QUALIFIED_NAME,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        LaravelSetList::LARAVEL_FACTORIES,
        LaravelSetList::LARAVEL_TESTING,
        // This one is *very* opinionated + noisy; enable only if you really want it:
        // LaravelSetList::LARAVEL_STATIC_TO_INJECTION,
    ])

    // Configurable rules (explicitly enabled).
    ->withConfiguredRule(RemoveDumpDataDeadCodeRector::class, [
        // add/remove whatever you consider "debug-only"
        'dd',
        'dump',
        'ray',
        'var_dump',
    ])
    ->withConfiguredRule(WhereToWhereLikeRector::class, [
        // Switch to true if you run Postgres and want ilike semantics.
        WhereToWhereLikeRector::USING_POSTGRES_DRIVER => false,
    ])
    ->withConfiguredRule(RouteActionCallableRector::class, [
        // Adjust if your controllers live elsewhere.
        RouteActionCallableRector::NAMESPACE => 'App\\Http\\Controllers',
        // Adjust if you have module-specific routes.
        RouteActionCallableRector::ROUTES => [
            __DIR__ . '/routes/web.php' => 'App\\Http\\Controllers',
            __DIR__ . '/routes/api.php' => 'App\\Http\\Controllers',
        ],
    ])

    // Opinionated rules (not part of any set). Add/remove depending on your style guide.
    ->withRules([
        RectorLaravel\Rector\MethodCall\ResponseHelperCallToJsonResponseRector::class,
        RectorLaravel\Rector\StaticCall\MinutesToSecondsInCacheRector::class,
        RectorLaravel\Rector\Empty_\EmptyToBlankAndFilledFuncRector::class,
    ]);
