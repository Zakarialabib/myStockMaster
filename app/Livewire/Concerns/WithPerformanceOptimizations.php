<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\WithPagination;

trait WithPerformanceOptimizations
{
    use WithPagination;

    public $loadingState = false;
    public $enableLazyLoading = true;
    public $cacheTimeout = 300; // 5 minutes
    public $debounceDelay = 500; // milliseconds

    /** Enable lazy loading for heavy operations */
    public function enableLazyLoading(): void
    {
        $this->enableLazyLoading = true;
    }

    /** Disable lazy loading */
    public function disableLazyLoading(): void
    {
        $this->enableLazyLoading = false;
    }

    /** Set loading state */
    public function setLoadingState(bool $loading): void
    {
        $this->loadingState = $loading;
    }

    /** Debounced search method */
    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->setLoadingState(true);

        // Reset loading state after a short delay
        $this->dispatch('reset-loading-state')->delay($this->debounceDelay);
    }

    /** Reset loading state listener */
    public function resetLoadingState(): void
    {
        $this->setLoadingState(false);
    }

    /** Cache key generator for computed properties */
    protected function getCacheKey(string $method, array $params = []): string
    {
        $baseKey = static::class.':'.$method;

        if ( ! empty($params)) {
            $baseKey .= ':'.md5(serialize($params));
        }

        return $baseKey;
    }

    /** Get cached result or execute callback */
    protected function getCachedResult(string $key, callable $callback, int $timeout = null)
    {
        $timeout ??= $this->cacheTimeout;

        return cache()->remember($key, $timeout, $callback);
    }

    /** Clear cache for specific key pattern */
    protected function clearCache(string $pattern = null): void
    {
        $pattern ??= static::class.':*';

        // Clear cache keys matching pattern
        $keys = cache()->getRedis()->keys($pattern);

        if ( ! empty($keys)) {
            cache()->getRedis()->del($keys);
        }
    }

    /** Optimize database queries with eager loading */
    protected function optimizeQuery($query, array $relations = [])
    {
        if ( ! empty($relations)) {
            $query->with($relations);
        }

        return $query;
    }

    /** Batch operations for better performance */
    protected function batchOperation(array $items, callable $callback, int $batchSize = 100): void
    {
        $chunks = array_chunk($items, $batchSize);

        foreach ($chunks as $chunk) {
            $callback($chunk);
        }
    }

    /** Memory usage optimization */
    protected function optimizeMemory(): void
    {
        // Clear any large arrays or objects that are no longer needed
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
    }

    /** Performance monitoring */
    protected function logPerformance(string $operation, callable $callback)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        $result = $callback();

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $executionTime = round(($endTime - $startTime) * 1000, 2); // milliseconds
        $memoryUsage = round(($endMemory - $startMemory) / 1024 / 1024, 2); // MB

        logger()->info("Performance: {$operation}", [
            'execution_time_ms' => $executionTime,
            'memory_usage_mb'   => $memoryUsage,
            'component'         => static::class,
        ]);

        return $result;
    }

    /** Listeners for performance optimizations */
    protected function getListeners(): array
    {
        return array_merge(parent::getListeners() ?? [], [
            'reset-loading-state' => 'resetLoadingState',
        ]);
    }
}
