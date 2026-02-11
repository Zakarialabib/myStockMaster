<?php

namespace App\Providers;

use App\Services\EnvironmentService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class EnvironmentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(EnvironmentService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureEnvironmentSettings();
        $this->ensureDesktopDirectories();
    }

    /**
     * Configure environment-specific settings
     */
    protected function configureEnvironmentSettings(): void
    {
        // Configure database connection based on environment
        if (EnvironmentService::isDesktop() && EnvironmentService::isOfflineMode()) {
            Config::set('database.default', 'sqlite_desktop');
        }

        // Configure session driver based on environment
        Config::set('session.driver', EnvironmentService::getSessionDriver());

        // Configure cache driver based on environment
        Config::set('cache.default', EnvironmentService::getCacheDriver());

        // Desktop-specific configurations
        if (EnvironmentService::isDesktop()) {
            $this->configureDesktopSettings();
        }
    }

    /**
     * Configure desktop-specific settings
     */
    protected function configureDesktopSettings(): void
    {
        $desktopConfig = EnvironmentService::getDesktopConfig();

        // Set desktop-specific session configuration
        Config::set('session.files', storage_path('framework/sessions/desktop'));
        Config::set('session.lifetime', 43200); // 12 hours for desktop
        Config::set('session.expire_on_close', false);

        // Set desktop-specific cache configuration
        Config::set('cache.stores.file.path', storage_path('framework/cache/desktop'));

        // Set desktop-specific logging
        Config::set('logging.channels.desktop', [
            'driver' => 'single',
            'path' => storage_path('logs/desktop.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ]);

        // Add desktop environment variables
        if (!env('DESKTOP_MODE')) {
            putenv('DESKTOP_MODE=true');
        }
    }

    /**
     * Ensure desktop-specific directories exist
     */
    protected function ensureDesktopDirectories(): void
    {
        if (EnvironmentService::isDesktop()) {
            $directories = [
                storage_path('database'),
                storage_path('framework/sessions/desktop'),
                storage_path('framework/cache/desktop'),
                storage_path('logs'),
            ];

            foreach ($directories as $directory) {
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
            }

            // Create desktop SQLite database if it doesn't exist
            $dbPath = storage_path('database/desktop.sqlite');
            if (!file_exists($dbPath)) {
                touch($dbPath);
            }
        }
    }
}