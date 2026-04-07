<?php

declare(strict_types=1);

namespace App\Providers;

use App\Native\Services\DesktopErrorHandler;
use App\Services\DatabaseSyncService;
use App\Services\EnvironmentService;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Native\Desktop\Facades\Window;
use Native\Laravel\Facades\Menu;
use Native\Laravel\Menu\Menu as NativeMenu;

class DesktopServiceProvider extends ServiceProvider
{
    /** Register services. */
    #[\Override]
    public function register(): void
    {
        // Register desktop error handler as singleton
        $this->app->singleton(fn($app): \App\Native\Services\DesktopErrorHandler => new DesktopErrorHandler);
    }

    /** Bootstrap services. */
    public function boot(): void
    {
        // Set database connection and broadcasting based on mode
        if (EnvironmentService::isDesktop()) {
            config(['database.default' => EnvironmentService::getDatabaseConnection()]);
            config(['broadcasting.default' => 'null']);
        }

        // Only configure desktop features if we're actually in a desktop environment
        if (EnvironmentService::isDesktop() && ! app()->runningInConsole() && ! app()->runningUnitTests()) {
            try {
                $this->configureDesktopEvents();
                
                if (class_exists(Menu::class)) {
                    Menu::new()
                        ->appMenu()
                        ->submenu('View', NativeMenu::new()
                            ->event(\Native\Laravel\Events\App\WindowToggled::class, 'Toggle Fullscreen', 'CmdOrCtrl+F')
                            ->event('native.navigate.dashboard', 'Dashboard', 'CmdOrCtrl+D')
                            ->event('native.navigate.settings', 'Settings', 'CmdOrCtrl+,')
                        )
                        ->submenu('Data', NativeMenu::new()
                            ->event('native.sync.trigger', 'Sync with Cloud', 'CmdOrCtrl+S')
                        )
                        ->register();
                }
            } catch (Exception $e) {
                Log::warning('Failed to configure NativePHP desktop features: ' . $e->getMessage());
            }
        }

        // Always setup error handling if in desktop mode (even without native service)
        if (EnvironmentService::isDesktop()) {
            $this->setupDesktopErrorHandling();
        }
    }

    /** Configure desktop-specific events. */
    private function configureDesktopEvents(): void
    {
        \Illuminate\Support\Facades\Event::listen('native.sync.online', function (): void {
            $this->syncWithOnline();
        });

        \Illuminate\Support\Facades\Event::listen('native.toggle.offline', function (): void {
            $this->toggleOfflineMode();
        });

        \Illuminate\Support\Facades\Event::listen('native.cache.clear', function (): void {
            $this->clearCache();
        });

        \Illuminate\Support\Facades\Event::listen('native.check.updates', function (): void {
            $this->checkForUpdates();
        });
    }

    /** Sync data with online database. */
    private function syncWithOnline(): void
    {
        try {
            $syncService = resolve(DatabaseSyncService::class);
            $syncService->syncToOnline();
            $this->showNotification('Sync Complete', 'Data synchronized with online database successfully.');
        } catch (Exception $exception) {
            $this->showNotification('Sync Failed', 'Failed to sync with online database: ' . $exception->getMessage());
        }
    }

    /** Toggle offline mode. */
    private function toggleOfflineMode(): void
    {
        $currentMode = EnvironmentService::isOfflineMode();
        $newMode = ! $currentMode;

        // Save the new mode to cache
        Cache::store('file')->forever('desktop_offline_mode', $newMode);

        $this->showNotification(
            'Mode Changed',
            $newMode ? 'Switched to Offline Mode' : 'Switched to Online Mode'
        );

        // Reload window to apply database connection change
        if (class_exists(\Native\Desktop\Facades\Window::class)) {
            try {
                // Short delay to allow notification to show
                \Illuminate\Support\Sleep::sleep(1);
                Window::current()->reload();
            } catch (Exception $e) {
                Log::warning('Failed to reload window after mode toggle: ' . $e->getMessage());
            }
        }
    }

    /** Clear application cache. */
    private function clearCache(): void
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            $this->showNotification('Cache Cleared', 'Application cache cleared successfully.');
        } catch (Exception $exception) {
            $this->showNotification('Cache Clear Failed', 'Failed to clear cache: ' . $exception->getMessage());
        }
    }

    /** Check for application updates. */
    private function checkForUpdates(): void
    {
        try {
            // Implementation for checking updates
            $this->showNotification('Update Check', 'Checking for updates...');
            // This would typically involve calling an update service
        } catch (Exception $exception) {
            $this->showNotification('Update Check Failed', 'Failed to check for updates: ' . $exception->getMessage());
        }
    }

    /** Show a desktop notification. */
    private function showNotification(string $title, string $message): void
    {
        try {
            if (class_exists(\Native\Desktop\Facades\Notification::class)) {
                \Native\Desktop\Facades\Notification::title($title)
                    ->message($message)
                    ->show();
            }
        } catch (Exception) {
            // Fallback to logging if notifications are not available
            Log::info(sprintf('Desktop Notification: %s - %s', $title, $message));
        }
    }

    /** Setup desktop-specific error handling */
    private function setupDesktopErrorHandling(): void
    {
        // Register custom error handler
        set_error_handler(function (int $severity, string $message, string $file, int $line): false {
            $isLivewireDiscoveryIncludeWarning = $severity === E_WARNING
                && str_contains($message, 'Failed opening')
                && str_contains($file, 'vendor\\composer\\ClassLoader.php')
                && (str_contains($message, 'app/Livewire/')
                    || str_contains($message, 'app\\Livewire\\'));

            $isDomDocumentWarning = $severity === E_WARNING
                && str_contains($message, 'DOMDocument::loadHTML()');

            if ($isLivewireDiscoveryIncludeWarning || $isDomDocumentWarning) {
                return false;
            }

            $desktopErrorHandler = resolve(DesktopErrorHandler::class);
            $desktopErrorHandler->handlePhpError($severity, $message, $file, $line);

            // Continue with default error handling
            return false;
        });

        // Register exception handler
        set_exception_handler(function (\Throwable $throwable): void {
            $desktopErrorHandler = resolve(DesktopErrorHandler::class);
            $desktopErrorHandler->handleException($throwable);

            Log::error('Uncaught exception in desktop mode', [
                'exception' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString(),
            ]);
        });
    }
}
