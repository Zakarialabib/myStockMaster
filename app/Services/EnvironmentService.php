<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

class EnvironmentService
{
    protected static ?bool $isDesktop = null;

    /** Check if the application is running in desktop mode (NativePHP) */
    public static function isDesktop(): bool
    {
        if (request()->header('X-Desktop-App') === 'true') {
            return true;
        }

        if (app()->runningUnitTests()) {
            return env('DESKTOP_MODE', false) || isset($_SERVER['NATIVEPHP_RUNNING']);
        }

        if (self::$isDesktop !== null) {
            return self::$isDesktop;
        }

        self::$isDesktop = (
            config('app.env') === 'desktop' ||
            env('DESKTOP_MODE', false) ||
            isset($_SERVER['NATIVEPHP_RUNNING'])
        );

        return self::$isDesktop;
    }

    public static function clearDesktopCache(): void
    {
        self::$isDesktop = null;
    }

    /** Check if the application is running in web mode */
    public static function isWeb(): bool
    {
        return ! self::isDesktop();
    }

    /** Get the current environment type */
    public static function getEnvironmentType(): string
    {
        return self::isDesktop() ? 'desktop' : 'web';
    }

    public static function getPlatform(): string
    {
        $platform = PHP_OS_FAMILY;

        if ($platform === 'Darwin') {
            return 'macos';
        }

        if ($platform === 'Windows') {
            return 'windows';
        }

        if ($platform === 'Linux') {
            return 'linux';
        }

        return strtolower($platform);
    }

    /** Check if offline mode is enabled for desktop */
    public static function isOfflineMode(): bool
    {
        if (! self::isDesktop()) {
            return false;
        }

        // Check cache first (for runtime toggling), then fallback to env
        // Using file cache driver for desktop to ensure persistence across restarts
        try {
            return \Illuminate\Support\Facades\Cache::store('file')->get(
                'desktop_offline_mode',
                env('DESKTOP_OFFLINE_MODE', true)
            );
        } catch (Exception $e) {
            return env('DESKTOP_OFFLINE_MODE', true);
        }
    }

    /** Get the appropriate database connection based on environment */
    public static function getDatabaseConnection(): string
    {
        if (self::isDesktop() && self::isOfflineMode()) {
            return 'sqlite_desktop';
        }

        return config('database.default', 'mysql');
    }

    /** Get the appropriate session driver based on environment */
    public static function getSessionDriver(): string
    {
        if (self::isDesktop()) {
            return 'file'; // Desktop uses file-based sessions
        }

        return config('session.driver', 'database');
    }

    /** Get the appropriate cache driver based on environment */
    public static function getCacheDriver(): string
    {
        if (self::isDesktop()) {
            return 'file'; // Desktop uses file-based cache
        }

        return config('cache.default', 'redis');
    }

    /** Check if data synchronization is needed */
    public static function needsDataSync(): bool
    {
        return self::isDesktop() &&
               self::isOfflineMode() &&
               env('DESKTOP_SYNC_ENABLED', true);
    }

    /** Get desktop-specific configuration */
    public static function getDesktopConfig(): array
    {
        return [
            'window' => [
                'width' => env('DESKTOP_WINDOW_WIDTH', 1200),
                'height' => env('DESKTOP_WINDOW_HEIGHT', 800),
                'resizable' => env('DESKTOP_WINDOW_RESIZABLE', true),
                'fullscreen' => env('DESKTOP_WINDOW_FULLSCREEN', false),
            ],
            'database' => [
                'path' => storage_path('database/desktop.sqlite'),
                'backup_path' => storage_path('database/desktop_backup.sqlite'),
            ],
            'sync' => [
                'interval' => env('DESKTOP_SYNC_INTERVAL', 300), // 5 minutes
                'auto_sync' => env('DESKTOP_AUTO_SYNC', true),
            ],
        ];
    }
}
