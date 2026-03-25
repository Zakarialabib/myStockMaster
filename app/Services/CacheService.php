<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    /** Default cache timeout in seconds (5 minutes) */
    public const DEFAULT_TIMEOUT = 300;

    /** Cache prefixes for different data types */
    public const PREFIXES = [
        'products' => 'products:',
        'categories' => 'categories:',
        'users' => 'users:',
        'reports' => 'reports:',
        'dashboard' => 'dashboard:',
        'filters' => 'filters:',
    ];

    /** Get cached data or execute callback */
    public static function remember(string $key, callable $callback, int $timeout = self::DEFAULT_TIMEOUT)
    {
        try {
            return Cache::remember($key, $timeout, $callback);
        } catch (Exception $e) {
            Log::warning('Cache remember failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);

            // Fallback to direct execution
            return $callback();
        }
    }

    /** Get cached data with prefix */
    public static function get(string $type, string $key, $default = null)
    {
        $prefixedKey = self::getPrefixedKey($type, $key);

        return Cache::get($prefixedKey, $default);
    }

    /** Store data in cache with prefix */
    public static function put(string $type, string $key, $value, int $timeout = self::DEFAULT_TIMEOUT): bool
    {
        try {
            $prefixedKey = self::getPrefixedKey($type, $key);

            return Cache::put($prefixedKey, $value, $timeout);
        } catch (Exception $e) {
            Log::warning('Cache put failed', [
                'type' => $type,
                'key' => $key,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /** Forget cached data with prefix */
    public static function forget(string $type, string $key): bool
    {
        try {
            $prefixedKey = self::getPrefixedKey($type, $key);

            return Cache::forget($prefixedKey);
        } catch (Exception $e) {
            Log::warning('Cache forget failed', [
                'type' => $type,
                'key' => $key,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /** Clear all cache for a specific type */
    public static function clearType(string $type): void
    {
        try {
            $prefix = self::PREFIXES[$type] ?? $type . ':';

            // Get all keys with the prefix
            $keys = Cache::getRedis()->keys($prefix . '*');

            if (! empty($keys)) {
                Cache::getRedis()->del($keys);
                Log::info('Cache cleared for type', ['type' => $type, 'keys_count' => count($keys)]);
            }
        } catch (Exception $e) {
            Log::warning('Cache clear type failed', [
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /** Clear all application cache */
    public static function clearAll(): void
    {
        try {
            Cache::flush();
            Log::info('All cache cleared');
        } catch (Exception $e) {
            Log::warning('Cache clear all failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /** Get cache statistics */
    public static function getStats(): array
    {
        try {
            $stats = [];

            foreach (self::PREFIXES as $type => $prefix) {
                $keys = Cache::getRedis()->keys($prefix . '*');
                $stats[$type] = count($keys);
            }

            return $stats;
        } catch (Exception $e) {
            Log::warning('Cache stats failed', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /** Cache products data */
    public static function cacheProducts(string $key, callable $callback, int $timeout = self::DEFAULT_TIMEOUT)
    {
        return self::remember(self::getPrefixedKey('products', $key), $callback, $timeout);
    }

    /** Cache categories data */
    public static function cacheCategories(string $key, callable $callback, int $timeout = self::DEFAULT_TIMEOUT)
    {
        return self::remember(self::getPrefixedKey('categories', $key), $callback, $timeout);
    }

    /** Cache dashboard data */
    public static function cacheDashboard(string $key, callable $callback, int $timeout = 600) // 10 minutes for dashboard
    {
        return self::remember(self::getPrefixedKey('dashboard', $key), $callback, $timeout);
    }

    /** Cache reports data */
    public static function cacheReports(string $key, callable $callback, int $timeout = 1800) // 30 minutes for reports
    {
        return self::remember(self::getPrefixedKey('reports', $key), $callback, $timeout);
    }

    /** Get prefixed cache key */
    private static function getPrefixedKey(string $type, string $key): string
    {
        $prefix = self::PREFIXES[$type] ?? $type . ':';

        return $prefix . $key;
    }

    /** Generate cache key from parameters */
    public static function generateKey(array $params): string
    {
        return md5(serialize($params));
    }

    /** Warm up cache for common queries */
    public static function warmUp(): void
    {
        try {
            // Warm up categories
            self::cacheCategories('all', function () {
                return \App\Models\Category::active()->get();
            });

            // Warm up product counts
            self::cacheProducts('counts', function () {
                return [
                    'total' => \App\Models\Product::count(),
                    'active' => \App\Models\Product::where('status', true)->count(),
                    'low_stock' => \App\Models\Product::where('quantity', '<', 10)->count(),
                ];
            });

            Log::info('Cache warmed up successfully');
        } catch (Exception $e) {
            Log::warning('Cache warm up failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
