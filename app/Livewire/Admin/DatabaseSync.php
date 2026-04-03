<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Services\DatabaseSyncService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DatabaseSync extends Component
{
    public bool $isOnline = false;

    public ?Carbon $lastSync = null;

    public array $syncLog = [];

    protected ?DatabaseSyncService $syncService = null;

    public function boot(DatabaseSyncService $syncService): void
    {
        $this->syncService = $syncService;
    }

    public function mount(): void
    {
        $this->checkOnlineStatus();
        $this->loadSyncHistory();
    }

    public function render(): mixed
    {
        return view('livewire.admin.database-sync');
    }

    /** Check if the online database is available. */
    public function checkOnlineStatus(): void
    {
        try {
            $this->isOnline = $this->syncService->isOnlineAvailable();
        } catch (Exception $e) {
            $this->isOnline = false;
            Log::error('Failed to check online status: ' . $e->getMessage());
        }
    }

    /** Load sync history from cache. */
    private function loadSyncHistory(): void
    {
        $rawLastSync = Cache::get('database_sync.last_sync');

        if ($rawLastSync instanceof Carbon) {
            $this->lastSync = $rawLastSync;
        } elseif (is_string($rawLastSync) && $rawLastSync !== '') {
            $this->lastSync = Carbon::parse($rawLastSync);
        } else {
            $this->lastSync = null;
        }

        $this->syncLog = Cache::get('database_sync.log', []);

        // Keep only the last 20 log entries
        $this->syncLog = array_slice($this->syncLog, -20);
    }

    /** Sync data from online to offline database. */
    public function syncToOffline(): void
    {
        if (! $this->isOnline) {
            $this->addToLog('error', 'Cannot sync to offline: Online database is not available');

            return;
        }

        try {
            $this->addToLog('info', 'Starting sync from online to offline database...');

            $result = $this->syncService->syncToOffline();

            if ($result) {
                $this->addToLog('success', 'Successfully synced data to offline database');
                $this->updateLastSync();

                // Show success notification
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => __('admin.database_sync.messages.sync_to_offline_success'),
                ]);
            } else {
                $this->addToLog('error', 'Failed to sync data to offline database');

                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => __('admin.database_sync.messages.sync_to_offline_failed'),
                ]);
            }
        } catch (Exception $e) {
            $this->addToLog('error', 'Sync to offline failed: ' . $e->getMessage());
            Log::error('Sync to offline failed', ['error' => $e->getMessage()]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('admin.database_sync.messages.sync_error', ['error' => $e->getMessage()]),
            ]);
        }
    }

    /** Sync data from offline to online database. */
    public function syncToOnline(): void
    {
        if (! $this->isOnline) {
            $this->addToLog('error', 'Cannot sync to online: Online database is not available');

            return;
        }

        try {
            $this->addToLog('info', 'Starting sync from offline to online database...');

            $result = $this->syncService->syncToOnline();

            if ($result) {
                $this->addToLog('success', 'Successfully synced data to online database');
                $this->updateLastSync();

                // Show success notification
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => __('admin.database_sync.messages.sync_to_online_success'),
                ]);
            } else {
                $this->addToLog('error', 'Failed to sync data to online database');

                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => __('admin.database_sync.messages.sync_to_online_failed'),
                ]);
            }
        } catch (Exception $e) {
            $this->addToLog('error', 'Sync to online failed: ' . $e->getMessage());
            Log::error('Sync to online failed', ['error' => $e->getMessage()]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('admin.database_sync.messages.sync_error', ['error' => $e->getMessage()]),
            ]);
        }
    }

    /** Perform bidirectional sync. */
    public function syncBidirectional(): void
    {
        if (! $this->isOnline) {
            $this->addToLog('error', 'Cannot perform bidirectional sync: Online database is not available');

            return;
        }

        try {
            $this->addToLog('info', 'Starting bidirectional sync...');

            // First sync from online to offline
            $toOfflineResult = $this->syncService->syncToOffline();

            if ($toOfflineResult) {
                $this->addToLog('success', 'Phase 1: Successfully synced from online to offline');

                // Then sync from offline to online
                $toOnlineResult = $this->syncService->syncToOnline();

                if ($toOnlineResult) {
                    $this->addToLog('success', 'Phase 2: Successfully synced from offline to online');
                    $this->addToLog('success', 'Bidirectional sync completed successfully');
                    $this->updateLastSync();

                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => __('admin.database_sync.messages.bidirectional_sync_success'),
                    ]);
                } else {
                    $this->addToLog('error', 'Phase 2: Failed to sync from offline to online');
                }
            } else {
                $this->addToLog('error', 'Phase 1: Failed to sync from online to offline');
            }
        } catch (Exception $e) {
            $this->addToLog('error', 'Bidirectional sync failed: ' . $e->getMessage());
            Log::error('Bidirectional sync failed', ['error' => $e->getMessage()]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('admin.database_sync.messages.sync_error', ['error' => $e->getMessage()]),
            ]);
        }
    }

    /** Clear sync log. */
    public function clearLog(): void
    {
        $this->syncLog = [];
        Cache::forget('database_sync.log');

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('admin.database_sync.messages.log_cleared'),
        ]);
    }

    /** Add entry to sync log. */
    private function addToLog(string $type, string $message): void
    {
        $entry = [
            'type' => $type,
            'message' => $message,
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $this->syncLog[] = $entry;

        // Keep only the last 20 entries
        $this->syncLog = array_slice($this->syncLog, -20);

        // Cache the log
        Cache::put('database_sync.log', $this->syncLog, now()->addDays(7));
    }

    /** Update last sync timestamp. */
    private function updateLastSync(): void
    {
        $this->lastSync = Carbon::now();
        Cache::put('database_sync.last_sync', $this->lastSync->toDateTimeString(), now()->addDays(30));
    }

    /** Refresh the component data. */
    public function refresh(): void
    {
        $this->checkOnlineStatus();
        $this->loadSyncHistory();

        $this->dispatch('notify', [
            'type' => 'info',
            'message' => __('admin.database_sync.messages.refreshed'),
        ]);
    }
}
