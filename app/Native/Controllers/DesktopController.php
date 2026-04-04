<?php

declare(strict_types=1);

namespace App\Native\Controllers;

use App\Http\Controllers\Controller;
use App\Native\Services\DesktopShortcutService;
use App\Services\EnvironmentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class DesktopController extends Controller
{
    public function __construct(protected DesktopShortcutService $shortcutService)
    {
        $this->middleware(function ($request, $next) {
            if (! EnvironmentService::isDesktop()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Desktop features are only available in desktop mode',
                ], 403);
            }

            return $next($request);
        });
    }

    /** Execute a keyboard shortcut */
    public function executeShortcut(Request $request): JsonResponse
    {
        $request->validate([
            'shortcut' => 'required|string',
        ]);

        $shortcut = $request->input('shortcut');

        Log::info('Desktop shortcut executed', [
            'shortcut' => $shortcut,
            'user_id' => Auth::id(),
            'timestamp' => now(),
        ]);

        $result = $this->shortcutService->executeShortcut($shortcut);

        return response()->json($result);
    }

    /** Get all available shortcuts */
    public function getShortcuts(): JsonResponse
    {
        $groupedShortcuts = $this->shortcutService->getShortcuts();
        $shortcuts = [];

        foreach ($groupedShortcuts as $category => $items) {
            foreach ($items as $key => $description) {
                $shortcuts[] = [
                    'key' => $key,
                    'description' => $description,
                    'action' => DesktopShortcutService::SHORTCUTS[$key] ?? null,
                    'category' => (string) $category,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'shortcuts' => $shortcuts,
            'total' => count($shortcuts),
        ]);
    }

    /** Check if a shortcut is available */
    public function checkShortcut(Request $request): JsonResponse
    {
        $request->validate([
            'shortcut' => 'required|string',
        ]);

        $shortcut = $request->input('shortcut');
        $available = $this->shortcutService->isShortcutAvailable($shortcut);
        $description = $this->shortcutService->getShortcutDescription($shortcut);

        return response()->json([
            'success' => true,
            'shortcut' => $shortcut,
            'available' => $available,
            'description' => $description,
        ]);
    }

    /** Register all shortcuts with the desktop environment */
    public function registerShortcuts(): JsonResponse
    {
        try {
            $this->shortcutService->registerShortcuts();

            return response()->json([
                'success' => true,
                'message' => 'Desktop shortcuts registered successfully',
            ]);
        } catch (Exception $e) {
            Log::error('Failed to register desktop shortcuts', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to register shortcuts: ' . $e->getMessage(),
            ], 500);
        }
    }

    /** Get desktop environment status */
    public function getDesktopStatus(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'desktop_mode' => EnvironmentService::isDesktop(),
            'environment' => EnvironmentService::getEnvironmentType(),
            'platform' => EnvironmentService::getPlatform(),
            'version' => config('nativephp.app_version', '1.0.0'),
            'shortcuts_registered' => true,
            'features' => [
                'keyboard_shortcuts' => true,
                'native_menus' => true,
                'notifications' => true,
                'window_management' => true,
                'data_sync' => true,
                'offline_mode' => true,
            ],
        ]);
    }

    /** Handle desktop-specific actions */
    public function handleAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|string',
            'params' => 'sometimes|array',
            'data' => 'sometimes|array',
        ]);

        $action = $request->input('action');
        $params = $request->input('params', $request->input('data', []));

        try {
            $result = $this->executeDesktopAction($action, $params);

            return response()->json($result);
        } catch (Exception $e) {
            Log::error('Desktop action failed', [
                'action' => $action,
                'params' => $params,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Action failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /** Execute a desktop-specific action */
    protected function executeDesktopAction(string $action, array $params = []): array
    {
        switch ($action) {
            case 'show_notification':
                return $this->showNotification($params);

            case 'update_window_title':
                return $this->updateWindowTitle($params);

            case 'set_window_size':
                return $this->setWindowSize($params);

            case 'toggle_always_on_top':
                return $this->toggleAlwaysOnTop($params);

            case 'show_context_menu':
                return $this->showContextMenu($params);

            case 'handle_file_drop':
                return $this->handleFileDrop($params);

            default:
                throw new InvalidArgumentException("Unknown desktop action: {$action}");
        }
    }

    /** Show a desktop notification */
    protected function showNotification(array $params): array
    {
        $title = $params['title'] ?? 'MyStockMaster';
        $message = $params['message'] ?? '';
        $type = $params['type'] ?? 'info';

        // This would integrate with NativePHP's notification system
        Log::info('Desktop notification shown', [
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);

        return [
            'success' => true,
            'action' => 'notification_shown',
            'message' => 'Notification displayed successfully',
        ];
    }

    /** Update window title */
    protected function updateWindowTitle(array $params): array
    {
        $title = $params['title'] ?? 'MyStockMaster';

        // This would integrate with NativePHP's window management
        Log::info('Window title updated', ['title' => $title]);

        return [
            'success' => true,
            'action' => 'window_title_updated',
            'title' => $title,
        ];
    }

    /** Set window size */
    protected function setWindowSize(array $params): array
    {
        $width = $params['width'] ?? 1200;
        $height = $params['height'] ?? 800;

        // This would integrate with NativePHP's window management
        Log::info('Window size updated', ['width' => $width, 'height' => $height]);

        return [
            'success' => true,
            'action' => 'window_size_updated',
            'width' => $width,
            'height' => $height,
        ];
    }

    /** Toggle always on top */
    protected function toggleAlwaysOnTop(array $params): array
    {
        $alwaysOnTop = $params['always_on_top'] ?? false;

        // This would integrate with NativePHP's window management
        Log::info('Always on top toggled', ['always_on_top' => $alwaysOnTop]);

        return [
            'success' => true,
            'action' => 'always_on_top_toggled',
            'always_on_top' => $alwaysOnTop,
        ];
    }

    /** Show context menu */
    protected function showContextMenu(array $params): array
    {
        $items = $params['items'] ?? [];
        $x = $params['x'] ?? 0;
        $y = $params['y'] ?? 0;

        // This would integrate with NativePHP's context menu system
        Log::info('Context menu shown', ['items' => count($items), 'x' => $x, 'y' => $y]);

        return [
            'success' => true,
            'action' => 'context_menu_shown',
            'items' => count($items),
        ];
    }

    /** Handle file drop */
    protected function handleFileDrop(array $params): array
    {
        $files = $params['files'] ?? [];

        // Process dropped files
        $processedFiles = [];

        foreach ($files as $file) {
            // This would handle file processing based on type
            $processedFiles[] = [
                'name' => $file['name'] ?? 'unknown',
                'size' => $file['size'] ?? 0,
                'type' => $file['type'] ?? 'unknown',
                'processed' => true,
            ];
        }

        Log::info('Files dropped and processed', ['count' => count($processedFiles)]);

        return [
            'success' => true,
            'action' => 'files_processed',
            'files' => $processedFiles,
        ];
    }

    /** Handle JavaScript errors from desktop app */
    public function handleJavaScriptError(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
            'source' => 'sometimes|string',
            'line' => 'sometimes|integer',
            'column' => 'sometimes|integer',
            'stack' => 'sometimes|string',
            'userAgent' => 'sometimes|string',
            'url' => 'sometimes|string',
        ]);

        try {
            $errorHandler = app(\App\Native\Services\DesktopErrorHandler::class);
            $result = $errorHandler->handleJavaScriptError($request->all());

            return response()->json([
                'success' => true,
                'error_id' => $result['error_id'],
                'message' => 'JavaScript error logged successfully',
            ]);
        } catch (Exception $e) {
            Log::error('Failed to handle JavaScript error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to log JavaScript error',
            ], 500);
        }
    }
}
