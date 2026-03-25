<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\CartStorage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

trait CartStorageTrait
{
    /** Primary storage driver (session, cache) */
    protected string $primaryStorage = 'session';

    /** Secondary storage driver for persistence (cache, database) */
    protected string $secondaryStorage = 'cache';

    /** Cache duration in minutes */
    protected int $cacheDuration = 1440; // 24 hours

    /** Whether to use hybrid storage (primary + secondary) */
    protected bool $useHybridStorage = true;

    /** Store cart content using hybrid storage strategy */
    protected function storeContent(Collection $content): void
    {
        // Always store in primary storage (fast access)
        $this->storeInPrimaryStorage($content);

        // Store in secondary storage for persistence if enabled
        if ($this->useHybridStorage && $this->secondaryStorage !== $this->primaryStorage) {
            $this->storeInSecondaryStorage($content);
        }
    }

    /** Retrieve cart content using hybrid storage strategy */
    protected function retrieveContent(): Collection
    {
        // Try primary storage first (fastest)
        $content = $this->retrieveFromPrimaryStorage();

        // If primary storage is empty and hybrid storage is enabled, try secondary
        if ($content->isEmpty() && $this->useHybridStorage && $this->secondaryStorage !== $this->primaryStorage) {
            $content = $this->retrieveFromSecondaryStorage();

            // If found in secondary, restore to primary for faster access
            if ($content->isNotEmpty()) {
                $this->storeInPrimaryStorage($content);
            }
        }

        return $content;
    }

    /** Store content in session */
    protected function storeInSession(Collection $content): void
    {
        Session::put($this->sessionKey, $content->toArray());
    }

    /** Retrieve content from session */
    protected function retrieveFromSession(): Collection
    {
        $data = Session::get($this->sessionKey, []);

        // Ensure we maintain the associative array structure
        return collect($data);
    }

    /** Store content in database */
    protected function storeInDatabase(Collection $content): void
    {
        CartStorage::updateOrCreate(
            ['session_key' => $this->sessionKey],
            [
                'cart_data' => $content->toJson(),
                'updated_at' => now(),
            ]
        );
    }

    /** Retrieve content from database */
    protected function retrieveFromDatabase(): Collection
    {
        $cartStorage = CartStorage::where('session_key', $this->sessionKey)->first();

        if (! $cartStorage) {
            return collect();
        }

        return collect(json_decode($cartStorage->cart_data, true) ?: []);
    }

    /** Store content in cache */
    protected function storeInCache(Collection $content): void
    {
        Cache::put($this->sessionKey, $content->toArray(), now()->addMinutes($this->cacheDuration));
    }

    /** Retrieve content from cache */
    protected function retrieveFromCache(): Collection
    {
        return collect(Cache::get($this->sessionKey, []));
    }

    /** Store content in primary storage */
    protected function storeInPrimaryStorage(Collection $content): void
    {
        switch ($this->primaryStorage) {
            case 'cache':
                $this->storeInCache($content);

                break;
            default:
                $this->storeInSession($content);

                break;
        }
    }

    /** Retrieve content from primary storage */
    protected function retrieveFromPrimaryStorage(): Collection
    {
        switch ($this->primaryStorage) {
            case 'cache':
                return $this->retrieveFromCache();
            default:
                return $this->retrieveFromSession();
        }
    }

    /** Store content in secondary storage */
    protected function storeInSecondaryStorage(Collection $content): void
    {
        switch ($this->secondaryStorage) {
            case 'database':
                $this->storeInDatabase($content);

                break;
            case 'session':
                $this->storeInSession($content);

                break;
            default:
                $this->storeInCache($content);

                break;
        }
    }

    /** Retrieve content from secondary storage */
    protected function retrieveFromSecondaryStorage(): Collection
    {
        switch ($this->secondaryStorage) {
            case 'database':
                return $this->retrieveFromDatabase();
            case 'session':
                return $this->retrieveFromSession();
            default:
                return $this->retrieveFromCache();
        }
    }

    /** Set primary storage driver */
    public function setPrimaryStorage(string $driver): self
    {
        $this->primaryStorage = $driver;

        return $this;
    }

    /** Set secondary storage driver */
    public function setSecondaryStorage(string $driver): self
    {
        $this->secondaryStorage = $driver;

        return $this;
    }

    /** Get primary storage driver */
    public function getPrimaryStorage(): string
    {
        return $this->primaryStorage;
    }

    /** Get secondary storage driver */
    public function getSecondaryStorage(): string
    {
        return $this->secondaryStorage;
    }

    /** Enable or disable hybrid storage */
    public function setHybridStorage(bool $enabled): self
    {
        $this->useHybridStorage = $enabled;

        return $this;
    }

    /** Set cache duration */
    public function setCacheDuration(int $minutes): self
    {
        $this->cacheDuration = $minutes;

        return $this;
    }

    /** Clear storage for current session */
    protected function clearStorage(): void
    {
        // Clear primary storage
        $this->clearPrimaryStorage();

        // Clear secondary storage if hybrid is enabled
        if ($this->useHybridStorage && $this->secondaryStorage !== $this->primaryStorage) {
            $this->clearSecondaryStorage();
        }
    }

    /** Clear primary storage */
    protected function clearPrimaryStorage(): void
    {
        switch ($this->primaryStorage) {
            case 'cache':
                Cache::forget($this->sessionKey);

                break;
            default:
                Session::forget($this->sessionKey);

                break;
        }
    }

    /** Clear secondary storage */
    protected function clearSecondaryStorage(): void
    {
        switch ($this->secondaryStorage) {
            case 'database':
                CartStorage::where('session_key', $this->sessionKey)->delete();

                break;
            case 'session':
                Session::forget($this->sessionKey);

                break;
            default:
                Cache::forget($this->sessionKey);

                break;
        }
    }

    /** Check if storage has content */
    protected function hasStorageContent(): bool
    {
        // Check primary storage first
        if ($this->hasPrimaryStorageContent()) {
            return true;
        }

        // Check secondary storage if hybrid is enabled
        if ($this->useHybridStorage && $this->secondaryStorage !== $this->primaryStorage) {
            return $this->hasSecondaryStorageContent();
        }

        return false;
    }

    /** Check if primary storage has content */
    protected function hasPrimaryStorageContent(): bool
    {
        switch ($this->primaryStorage) {
            case 'cache':
                return Cache::has($this->sessionKey);
            default:
                return Session::has($this->sessionKey);
        }
    }

    /** Check if secondary storage has content */
    protected function hasSecondaryStorageContent(): bool
    {
        switch ($this->secondaryStorage) {
            case 'database':
                return CartStorage::where('session_key', $this->sessionKey)->exists();
            case 'session':
                return Session::has($this->sessionKey);
            default:
                return Cache::has($this->sessionKey);
        }
    }

    /** Migrate cart from one storage configuration to another */
    public function migrateStorage(string $fromPrimary, string $toPrimary, ?string $fromSecondary = null, ?string $toSecondary = null): bool
    {
        $originalPrimary = $this->primaryStorage;
        $originalSecondary = $this->secondaryStorage;

        // Get content from source storage
        $this->primaryStorage = $fromPrimary;

        if ($fromSecondary) {
            $this->secondaryStorage = $fromSecondary;
        }
        $content = $this->retrieveContent();

        if ($content->isEmpty()) {
            return false;
        }

        // Store content in destination storage
        $this->primaryStorage = $toPrimary;

        if ($toSecondary) {
            $this->secondaryStorage = $toSecondary;
        }
        $this->storeContent($content);

        // Clear source storage
        $this->primaryStorage = $fromPrimary;

        if ($fromSecondary) {
            $this->secondaryStorage = $fromSecondary;
        }
        $this->clearStorage();

        // Set final storage configuration
        $this->primaryStorage = $toPrimary;

        if ($toSecondary) {
            $this->secondaryStorage = $toSecondary;
        }

        return true;
    }

    /** Get storage statistics */
    public function getStorageStats(): array
    {
        $content = $this->retrieveContent();

        return [
            'primary_storage' => $this->primaryStorage,
            'secondary_storage' => $this->secondaryStorage,
            'hybrid_enabled' => $this->useHybridStorage,
            'session_key' => $this->sessionKey,
            'item_count' => $content->count(),
            'total_quantity' => $content->sum('quantity'),
            'storage_size' => strlen(json_encode($content->toArray())),
            'last_updated' => $content->max('updated_at'),
            'primary_has_content' => $this->hasPrimaryStorageContent(),
            'secondary_has_content' => $this->useHybridStorage ? $this->hasSecondaryStorageContent() : false,
        ];
    }
}
