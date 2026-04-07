<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DatabaseSyncService;
use App\Services\EnvironmentService;
use Illuminate\Console\Command;

class SyncDesktopDatabase extends Command
{
    /** The name and signature of the console command. */
    protected $signature = 'desktop:sync {direction=both : Sync direction: to-offline, to-online, or both}';

    /** The console command description. */
    protected $description = 'Sync data between online and offline databases';

    /** Execute the console command. */
    public function handle(DatabaseSyncService $databaseSyncService): int
    {
        if (! EnvironmentService::isDesktop()) {
            $this->error('This command can only be run in desktop mode.');

            return self::FAILURE;
        }

        $direction = $this->argument('direction');

        if (! in_array($direction, ['to-offline', 'to-online', 'both'])) {
            $this->error('Invalid direction. Use: to-offline, to-online, or both');

            return self::FAILURE;
        }

        // Check online availability
        if (! $databaseSyncService->isOnlineAvailable()) {
            $this->error('Online database is not available. Cannot perform sync.');

            return self::FAILURE;
        }

        $this->info(sprintf('Starting database sync (%s)...', $direction));

        $success = true;

        // Sync to offline
        if ($direction === 'to-offline' || $direction === 'both') {
            $this->info('📥 Syncing data to offline database...');

            if ($databaseSyncService->syncToOffline()) {
                $this->info('✅ Data synced to offline database successfully!');
            } else {
                $this->error('❌ Failed to sync data to offline database.');
                $success = false;
            }
        }

        // Sync to online
        if ($direction === 'to-online' || $direction === 'both') {
            $this->info('📤 Syncing data to online database...');

            if ($databaseSyncService->syncToOnline()) {
                $this->info('✅ Data synced to online database successfully!');
            } else {
                $this->error('❌ Failed to sync data to online database.');
                $success = false;
            }
        }

        // Show sync status
        $this->showSyncStatus($databaseSyncService);

        return $success ? self::SUCCESS : self::FAILURE;
    }

    /** Show current sync status */
    protected function showSyncStatus(DatabaseSyncService $databaseSyncService): void
    {
        $status = $databaseSyncService->getSyncStatus();

        $this->newLine();
        $this->info('📊 Sync Status:');
        $this->table(
            ['Property', 'Value'],
            [
                ['Online Available', $status['online_available'] ? '✅ Yes' : '❌ No'],
                ['Desktop Mode', $status['desktop_mode'] ? '✅ Yes' : '❌ No'],
                ['Offline Mode', $status['offline_mode'] ? '✅ Yes' : '❌ No'],
                ['Sync Needed', $status['sync_needed'] ? '✅ Yes' : '❌ No'],
                ['Last Sync', $status['last_sync']],
            ]
        );
    }
}
