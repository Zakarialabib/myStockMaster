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
    public function register(): void
    {
        // Register desktop error handler as singleton
        $this->app->singleton(DesktopErrorHandler::class, function ($app) {
            return new DesktopErrorHandler;
        });
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
        \Illuminate\Support\Facades\Event::listen('native.sync.online', function () {
            $this->syncWithOnline();
        });

        \Illuminate\Support\Facades\Event::listen('native.toggle.offline', function () {
            $this->toggleOfflineMode();
        });

        \Illuminate\Support\Facades\Event::listen('native.cache.clear', function () {
            $this->clearCache();
        });

        \Illuminate\Support\Facades\Event::listen('native.check.updates', function () {
            $this->checkForUpdates();
        });
    }

    /** Sync data with online database. */
    private function syncWithOnline(): void
    {
        try {
            $syncService = app(DatabaseSyncService::class);
            $syncService->syncToOnline();
            $this->showNotification('Sync Complete', 'Data synchronized with online database successfully.');
        } catch (Exception $e) {
            $this->showNotification('Sync Failed', 'Failed to sync with online database: ' . $e->getMessage());
        }
    }

    /** Show database sync dialog. */
    private function showDatabaseSyncDialog(): void
    {
        // This would open a dialog for database sync options
        redirect()->route('admin.database-sync');
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
        if (class_exists('\Native\Desktop\Facades\Window')) {
            try {
                // Short delay to allow notification to show
                sleep(1);
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
        } catch (Exception $e) {
            $this->showNotification('Cache Clear Failed', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /** Show about dialog. */
    private function showAboutDialog(): void
    {
        // This would show an about dialog with app information
        redirect()->route('about');
    }

    /** Report an issue. */
    private function reportIssue(): void
    {
        // This would open issue reporting functionality
        redirect()->route('support.report-issue');
    }

    /** Check for application updates. */
    private function checkForUpdates(): void
    {
        try {
            // Implementation for checking updates
            $this->showNotification('Update Check', 'Checking for updates...');
            // This would typically involve calling an update service
        } catch (Exception $e) {
            $this->showNotification('Update Check Failed', 'Failed to check for updates: ' . $e->getMessage());
        }
    }

    /** Show a desktop notification. */
    private function showNotification(string $title, string $message): void
    {
        try {
            if (class_exists('\Native\Desktop\Facades\Notification')) {
                \Native\Desktop\Facades\Notification::title($title)
                    ->message($message)
                    ->show();
            }
        } catch (Exception $e) {
            // Fallback to logging if notifications are not available
            Log::info("Desktop Notification: {$title} - {$message}");
        }
    }

    /** Setup desktop-specific error handling */
    private function setupDesktopErrorHandling(): void
    {
        // Register custom error handler
        set_error_handler(function ($severity, $message, $file, $line) {
            $isLivewireDiscoveryIncludeWarning = $severity === E_WARNING
                && str_contains((string) $message, 'Failed opening')
                && str_contains((string) $file, 'vendor\\composer\\ClassLoader.php')
                && (str_contains((string) $message, 'app/Livewire/')
                    || str_contains((string) $message, 'app\\Livewire\\'));

            $isDomDocumentWarning = $severity === E_WARNING
                && str_contains((string) $message, 'DOMDocument::loadHTML()');

            if ($isLivewireDiscoveryIncludeWarning || $isDomDocumentWarning) {
                return false;
            }

            $errorHandler = app(DesktopErrorHandler::class);
            $errorHandler->handlePhpError($severity, $message, $file, $line);

            // Continue with default error handling
            return false;
        });

        // Register exception handler
        set_exception_handler(function ($exception) {
            $errorHandler = app(DesktopErrorHandler::class);
            $errorHandler->handleException($exception);

            Log::error('Uncaught exception in desktop mode', [
                'exception' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);
        });
    }
}
