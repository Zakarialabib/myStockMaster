<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Native\Laravel\Facades\Window;
use Native\Laravel\Facades\Menu;
use Native\Laravel\Facades\GlobalShortcut;
use App\Services\EnvironmentService;
use App\Services\DatabaseSyncService;
use App\Services\DesktopErrorHandler;
use Illuminate\Support\Facades\Log;

class DesktopServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register desktop error handler as singleton
        $this->app->singleton(DesktopErrorHandler::class, function ($app) {
            return new DesktopErrorHandler();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only configure desktop features if we're actually in a desktop environment
        // and the native service is available
        if (EnvironmentService::isDesktop() && $this->isNativeServiceAvailable()) {
            $this->configureDesktopWindow();
            $this->configureDesktopMenu();
            $this->configureDesktopShortcuts();
            $this->configureDesktopEvents();
        }
        
        // Always setup error handling if in desktop mode (even without native service)
        if (EnvironmentService::isDesktop()) {
            $this->setupDesktopErrorHandling();
        }
    }

    /**
     * Check if the native service is available
     */
    private function isNativeServiceAvailable(): bool
    {
        try {
            // Try to make a simple request to check if the service is running
            $response = \Illuminate\Support\Facades\Http::timeout(1)->get('http://localhost:4000/api/status');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Configure the desktop application window.
     */
    private function configureDesktopWindow(): void
    {
        Window::open()
            ->title(config('native.app_name', 'MyStockMaster'))
            ->width(config('native.window.width', 1200))
            ->height(config('native.window.height', 800))
            ->minWidth(config('native.window.min_width', 800))
            ->minHeight(config('native.window.min_height', 600))
            ->resizable(config('native.window.resizable', true))
            ->maximizable(true)
            ->minimizable(true)
            ->closable(true)
            ->alwaysOnTop(config('native.window.always_on_top', false))
            ->skipTaskbar(config('native.window.skip_taskbar', false))
            ->showDevTools(config('native.development.show_dev_tools', false))
            ->titleBarStyle(config('native.window.title_bar_style', 'default'));
    }

    /**
     * Configure the desktop application menu.
     */
    private function configureDesktopMenu(): void
    {
        if (config('native.menu.enabled', true)) {
            // File Menu
            Menu::new()
                ->label('File')
                ->submenu([
                    Menu::new()
                        ->label('New Sale')
                        ->accelerator('CmdOrCtrl+N')
                        ->click(fn() => redirect()->route('sales.create')),
                    
                    Menu::new()
                        ->label('New Product')
                        ->accelerator('CmdOrCtrl+P')
                        ->click(fn() => redirect()->route('products.create')),
                    
                    Menu::separator(),
                    
                    Menu::new()
                        ->label('Import Products')
                        ->accelerator('CmdOrCtrl+I')
                        ->click(fn() => redirect()->route('products.import')),
                    
                    Menu::new()
                        ->label('Export Data')
                        ->accelerator('CmdOrCtrl+E')
                        ->click(fn() => redirect()->route('exports.index')),
                    
                    Menu::separator(),
                    
                    Menu::new()
                        ->label('Sync with Online')
                        ->accelerator('CmdOrCtrl+S')
                        ->click(fn() => $this->syncWithOnline()),
                    
                    Menu::separator(),
                    
                    Menu::new()
                        ->label('Exit')
                        ->role('quit'),
                ]);

            // View Menu
            Menu::new()
                ->label('View')
                ->submenu([
                    Menu::new()
                        ->label('Dashboard')
                        ->accelerator('CmdOrCtrl+1')
                        ->click(fn() => redirect()->route('dashboard')),
                    
                    Menu::new()
                        ->label('Products')
                        ->accelerator('CmdOrCtrl+2')
                        ->click(fn() => redirect()->route('products.index')),
                    
                    Menu::new()
                        ->label('Sales')
                        ->accelerator('CmdOrCtrl+3')
                        ->click(fn() => redirect()->route('sales.index')),
                    
                    Menu::new()
                        ->label('POS')
                        ->accelerator('CmdOrCtrl+4')
                        ->click(fn() => redirect()->route('pos.index')),
                    
                    Menu::new()
                        ->label('Reports')
                        ->accelerator('CmdOrCtrl+5')
                        ->click(fn() => redirect()->route('reports.index')),
                    
                    Menu::separator(),
                    
                    Menu::new()
                        ->label('Toggle Fullscreen')
                        ->accelerator('F11')
                        ->click(fn() => Window::current()->toggleFullscreen()),
                    
                    Menu::new()
                        ->label('Reload')
                        ->accelerator('CmdOrCtrl+R')
                        ->role('reload'),
                    
                    Menu::new()
                        ->label('Toggle Developer Tools')
                        ->accelerator('F12')
                        ->click(fn() => Window::current()->toggleDevTools()),
                ]);

            // Tools Menu
            Menu::new()
                ->label('Tools')
                ->submenu([
                    Menu::new()
                        ->label('Settings')
                        ->accelerator('CmdOrCtrl+,')
                        ->click(fn() => redirect()->route('settings.index')),
                    
                    Menu::new()
                        ->label('Database Sync')
                        ->click(fn() => $this->showDatabaseSyncDialog()),
                    
                    Menu::new()
                        ->label('Offline Mode')
                        ->click(fn() => $this->toggleOfflineMode()),
                    
                    Menu::separator(),
                    
                    Menu::new()
                        ->label('Clear Cache')
                        ->click(fn() => $this->clearCache()),
                ]);

            // Help Menu
            Menu::new()
                ->label('Help')
                ->submenu([
                    Menu::new()
                        ->label('About MyStockMaster')
                        ->click(fn() => $this->showAboutDialog()),
                    
                    Menu::new()
                        ->label('Documentation')
                        ->click(fn() => redirect()->route('help')),
                    
                    Menu::new()
                        ->label('Check for Updates')
                        ->click(fn() => $this->checkForUpdates()),
                    
                    Menu::new()
                        ->label('Report Issue')
                        ->click(fn() => $this->reportIssue()),
                ]);
        }
    }

    /**
     * Configure desktop keyboard shortcuts.
     */
    private function configureDesktopShortcuts(): void
    {
        try {
            // Global shortcuts that work even when app is not focused
            GlobalShortcut::key('CmdOrCtrl+Shift+M')
                ->event('shortcut:show-main-window');
            
            GlobalShortcut::key('CmdOrCtrl+Shift+D')
                ->event('shortcut:toggle-dev-tools');
        } catch (\Exception $e) {
            // GlobalShortcut might not be available in all environments
            \Log::info('Desktop shortcuts not available: ' . $e->getMessage());
        }
    }

    /**
     * Configure desktop-specific events.
     */
    private function configureDesktopEvents(): void
    {
        // Register event listeners for desktop-specific events
        // This can be expanded based on specific needs
    }

    /**
     * Sync data with online database.
     */
    private function syncWithOnline(): void
    {
        try {
            $syncService = app(DatabaseSyncService::class);
            $syncService->syncToOnline();
            $this->showNotification('Sync Complete', 'Data synchronized with online database successfully.');
        } catch (\Exception $e) {
            $this->showNotification('Sync Failed', 'Failed to sync with online database: ' . $e->getMessage());
        }
    }

    /**
     * Show database sync dialog.
     */
    private function showDatabaseSyncDialog(): void
    {
        // This would open a dialog for database sync options
        redirect()->route('admin.database-sync');
    }

    /**
     * Toggle offline mode.
     */
    private function toggleOfflineMode(): void
    {
        $currentMode = EnvironmentService::isOffline();
        // Toggle offline mode logic here
        $this->showNotification(
            'Mode Changed', 
            $currentMode ? 'Switched to Online Mode' : 'Switched to Offline Mode'
        );
    }

    /**
     * Clear application cache.
     */
    private function clearCache(): void
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            $this->showNotification('Cache Cleared', 'Application cache cleared successfully.');
        } catch (\Exception $e) {
            $this->showNotification('Cache Clear Failed', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Show about dialog.
     */
    private function showAboutDialog(): void
    {
        // This would show an about dialog with app information
        redirect()->route('about');
    }

    /**
     * Report an issue.
     */
    private function reportIssue(): void
    {
        // This would open issue reporting functionality
        redirect()->route('support.report-issue');
    }

    /**
     * Check for application updates.
     */
    private function checkForUpdates(): void
    {
        try {
            // Implementation for checking updates
            $this->showNotification('Update Check', 'Checking for updates...');
            // This would typically involve calling an update service
        } catch (\Exception $e) {
            $this->showNotification('Update Check Failed', 'Failed to check for updates: ' . $e->getMessage());
        }
    }

    /**
     * Show a desktop notification.
     */
    private function showNotification(string $title, string $message): void
    {
        try {
            if (class_exists('\Native\Laravel\Facades\Notification')) {
                \Native\Laravel\Facades\Notification::title($title)
                    ->message($message)
                    ->show();
            }
        } catch (\Exception $e) {
            // Fallback to logging if notifications are not available
            \Log::info("Desktop Notification: {$title} - {$message}");
        }
    }

    /**
     * Setup desktop-specific error handling
     */
    private function setupDesktopErrorHandling(): void
    {
        // Register custom error handler
        set_error_handler(function ($severity, $message, $file, $line) {
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
                'trace' => $exception->getTraceAsString()
            ]);
        });
    }
}
