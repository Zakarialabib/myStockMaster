<?php

declare(strict_types=1);

namespace App\Native\Services;

use App\Services\DatabaseSyncService;
use App\Services\EnvironmentService;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DesktopShortcutService
{
    /** Available keyboard shortcuts for desktop mode */
    public const SHORTCUTS = [
        // Navigation shortcuts
        'ctrl+d' => 'toggleDevTools',
        'ctrl+shift+d' => 'toggleDevTools',
        'ctrl+r' => 'refreshPage',
        'f5' => 'refreshPage',
        'ctrl+shift+r' => 'hardRefresh',
        'f11' => 'toggleFullscreen',
        'alt+f4' => 'closeWindow',
        'ctrl+m' => 'minimizeWindow',
        'ctrl+shift+m' => 'maximizeWindow',

        // Application shortcuts
        'ctrl+shift+s' => 'syncData',
        'ctrl+shift+o' => 'toggleOfflineMode',
        'ctrl+shift+n' => 'showNotifications',
        'ctrl+shift+c' => 'clearCache',
        'ctrl+shift+l' => 'showLogs',

        // POS shortcuts
        'ctrl+shift+p' => 'openPOS',
        'ctrl+shift+i' => 'addProduct',
        'ctrl+shift+w' => 'showWarehouses',

        // Quick actions
        'ctrl+n' => 'newSale',
        'ctrl+shift+q' => 'newQuotation',
        'ctrl+shift+b' => 'showBarcode',
        'ctrl+shift+e' => 'exportData',

        // System shortcuts
        'ctrl+shift+?' => 'showHelp',
        'ctrl+shift+a' => 'showAbout',
        'ctrl+shift+u' => 'checkUpdates',
        'escape' => 'closeModal',
    ];

    /** Execute a shortcut action */
    public function executeShortcut(string $shortcut): array
    {
        if (! EnvironmentService::isDesktop()) {
            return [
                'success' => false,
                'action' => null,
                'message' => 'Desktop shortcuts only available in desktop mode',
            ];
        }

        $action = self::SHORTCUTS[$shortcut] ?? null;

        if (! $action) {
            return [
                'success' => false,
                'action' => null,
                'message' => 'Unknown shortcut: ' . $shortcut,
            ];
        }

        try {
            return $this->$action();
        } catch (Exception $exception) {
            Log::error('Desktop shortcut error', [
                'shortcut' => $shortcut,
                'action' => $action,
                'error' => $exception->getMessage(),
            ]);

            return [
                'success' => false,
                'action' => $action,
                'message' => 'Failed to execute shortcut: ' . $exception->getMessage(),
            ];
        }
    }

    /** Get all available shortcuts with descriptions */
    public function getShortcuts(): array
    {
        return [
            'Navigation' => [
                'ctrl+d' => 'Toggle Developer Tools',
                'ctrl+shift+d' => 'Toggle Developer Tools',
                'ctrl+r' => 'Refresh Page',
                'f5' => 'Refresh Page',
                'ctrl+shift+r' => 'Hard Refresh (Clear Cache)',
                'f11' => 'Toggle Fullscreen',
                'alt+f4' => 'Close Window',
                'ctrl+m' => 'Minimize Window',
                'ctrl+shift+m' => 'Maximize Window',
            ],
            'Application' => [
                'ctrl+shift+s' => 'Sync Data',
                'ctrl+shift+o' => 'Toggle Offline Mode',
                'ctrl+shift+n' => 'Show Notifications',
                'ctrl+shift+c' => 'Clear Cache',
                'ctrl+shift+l' => 'Show Logs',
            ],
            'POS & Inventory' => [
                'ctrl+shift+p' => 'Open POS',
                'ctrl+shift+i' => 'Add Product',
                'ctrl+n' => 'New Sale',
                'ctrl+shift+q' => 'New Quotation',
                'ctrl+shift+b' => 'Show Barcode Generator',
            ],
            'Management' => [
                'ctrl+shift+u' => 'Show Users',
                'ctrl+shift+w' => 'Show Warehouses',
                'ctrl+shift+e' => 'Export Data',
            ],
            'System' => [
                'ctrl+shift+?' => 'Show Help',
                'ctrl+shift+a' => 'Show About',
                'ctrl+shift+u' => 'Check for Updates',
                'escape' => 'Close Modal/Dialog',
            ],
        ];
    }

    // Window management actions
    protected function toggleDevTools(): array
    {
        return ['success' => true, 'action' => 'toggleDevTools', 'message' => 'Developer tools toggled'];
    }

    protected function refreshPage(): array
    {
        return ['success' => true, 'action' => 'refresh', 'message' => 'Page refreshed'];
    }

    protected function hardRefresh(): array
    {
        Cache::flush();

        return ['success' => true, 'action' => 'hardRefresh', 'message' => 'Hard refresh completed'];
    }

    protected function toggleFullscreen(): array
    {
        return ['success' => true, 'action' => 'toggleFullscreen', 'message' => 'Fullscreen toggled'];
    }

    protected function closeWindow(): array
    {
        return ['success' => true, 'action' => 'closeWindow', 'message' => 'Closing window'];
    }

    protected function minimizeWindow(): array
    {
        return ['success' => true, 'action' => 'minimizeWindow', 'message' => 'Window minimized'];
    }

    protected function maximizeWindow(): array
    {
        return ['success' => true, 'action' => 'maximizeWindow', 'message' => 'Window maximized'];
    }

    // Application actions
    protected function syncData(): array
    {
        try {
            $syncService = resolve(DatabaseSyncService::class);

            if (! $syncService->isOnlineAvailable()) {
                return ['success' => false, 'message' => 'Cannot sync: No internet connection'];
            }

            $toOfflineResult = $syncService->syncToOffline();
            $toOnlineResult = $syncService->syncToOnline();

            return [
                'success' => $toOfflineResult && $toOnlineResult,
                'action' => 'syncData',
                'message' => 'Data synchronization completed',
                'data' => [
                    'to_offline' => $toOfflineResult,
                    'to_online' => $toOnlineResult,
                ],
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Sync failed: ' . $exception->getMessage()];
        }
    }

    protected function toggleOfflineMode(): array
    {
        $currentMode = Cache::get('desktop_offline_mode', false);
        $newMode = ! $currentMode;

        Cache::put('desktop_offline_mode', $newMode, now()->addDays(30));

        return [
            'success' => true,
            'action' => 'toggleOfflineMode',
            'message' => $newMode ? 'Switched to offline mode' : 'Switched to online mode',
            'offline_mode' => $newMode,
        ];
    }

    protected function showNotifications(): array
    {
        return ['success' => true, 'action' => 'showNotifications', 'message' => 'Notifications panel opened'];
    }

    protected function clearCache(): array
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return ['success' => true, 'action' => 'clearCache', 'message' => 'All caches cleared successfully'];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Failed to clear cache: ' . $exception->getMessage()];
        }
    }

    protected function showLogs(): array
    {
        return ['success' => true, 'action' => 'navigate', 'url' => '/admin/logs', 'message' => 'Opening logs'];
    }

    // Navigation actions
    protected function openPOS(): array
    {
        return ['success' => true, 'action' => 'navigate', 'url' => '/pos', 'message' => 'Opening POS'];
    }

    protected function addProduct(): array
    {
        return ['success' => true, 'action' => 'navigate', 'url' => '/admin/products/create', 'message' => 'Opening product creation'];
    }

    protected function showUsers(): array
    {
        return ['success' => true, 'action' => 'navigate', 'url' => '/admin/users', 'message' => 'Opening users management'];
    }

    protected function showWarehouses(): array
    {
        return ['success' => true, 'action' => 'navigate', 'url' => '/admin/warehouses', 'message' => 'Opening warehouses'];
    }

    // Quick actions
    protected function newSale(): array
    {
        return ['success' => true, 'action' => 'navigate', 'url' => '/admin/sales/create', 'message' => 'Creating new sale'];
    }

    protected function newQuotation(): array
    {
        return ['success' => true, 'action' => 'navigate', 'url' => '/admin/quotations/create', 'message' => 'Creating new quotation'];
    }

    protected function showBarcode(): array
    {
        return ['success' => true, 'action' => 'navigate', 'url' => '/admin/barcodes', 'message' => 'Opening barcode generator'];
    }

    protected function exportData(): array
    {
        return ['success' => true, 'action' => 'showModal', 'modal' => 'export-data', 'message' => 'Opening export dialog'];
    }

    // System actions
    protected function showHelp(): array
    {
        return ['success' => true, 'action' => 'showModal', 'modal' => 'help', 'message' => 'Opening help'];
    }

    protected function showAbout(): array
    {
        return ['success' => true, 'action' => 'showModal', 'modal' => 'about', 'message' => 'Opening about dialog'];
    }

    protected function checkUpdates(): array
    {
        return ['success' => true, 'action' => 'checkUpdates', 'message' => 'Checking for updates'];
    }

    protected function closeModal(): array
    {
        return ['success' => true, 'action' => 'closeModal', 'message' => 'Modal closed'];
    }

    /** Register shortcuts with the desktop environment */
    public function registerShortcuts(): void
    {
        if (! EnvironmentService::isDesktop()) {
            return;
        }

        // This would integrate with NativePHP's shortcut registration
        // For now, we'll log the registration
        Log::info('Desktop shortcuts registered', [
            'shortcuts' => array_keys(self::SHORTCUTS),
            'count' => count(self::SHORTCUTS),
        ]);
    }

    /** Check if a shortcut is available */
    public function isShortcutAvailable(string $shortcut): bool
    {
        return isset(self::SHORTCUTS[$shortcut]);
    }

    /** Get shortcut description */
    public function getShortcutDescription(string $shortcut): ?string
    {
        $shortcuts = $this->getShortcuts();

        foreach ($shortcuts as $categoryShortcuts) {
            if (isset($categoryShortcuts[$shortcut])) {
                return $categoryShortcuts[$shortcut];
            }
        }

        return null;
    }
}
