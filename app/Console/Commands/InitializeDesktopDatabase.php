<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DatabaseSyncService;
use App\Services\EnvironmentService;
use Illuminate\Console\Command;

class InitializeDesktopDatabase extends Command
{
    /** The name and signature of the console command. */
    protected $signature = 'desktop:init-db {--force : Force initialization even if database exists}';

    /** The console command description. */
    protected $description = 'Initialize desktop SQLite database for offline mode';

    /** Execute the console command. */
    public function handle(DatabaseSyncService $syncService): int
    {
        if (! EnvironmentService::isDesktop()) {
            $this->error('This command can only be run in desktop mode.');

            return self::FAILURE;
        }

        $this->info('Initializing desktop database...');

        // Check if database already exists
        $dbPath = storage_path('database/desktop.sqlite');

        if (file_exists($dbPath) && ! $this->option('force')) {
            if (! $this->confirm('Desktop database already exists. Do you want to reinitialize it?')) {
                $this->info('Database initialization cancelled.');

                return self::SUCCESS;
            }
        }

        // Create database file if it doesn't exist
        if (! file_exists($dbPath)) {
            $this->info('Creating desktop database file...');

            if (! is_dir(dirname($dbPath))) {
                mkdir(dirname($dbPath), 0755, true);
            }
            touch($dbPath);
        }

        // Initialize database schema
        $this->info('Setting up database schema...');

        if ($syncService->initializeDesktopDatabase()) {
            $this->info('✅ Desktop database initialized successfully!');

            // Offer to sync data
            if ($syncService->isOnlineAvailable() && $this->confirm('Do you want to sync data from online database?')) {
                $this->info('Syncing data from online database...');

                if ($syncService->syncToOffline()) {
                    $this->info('✅ Data synced successfully!');
                } else {
                    $this->warn('⚠️  Data sync failed. Check logs for details.');
                }
            }

            return self::SUCCESS;
        } else {
            $this->error('❌ Failed to initialize desktop database. Check logs for details.');

            return self::FAILURE;
        }
    }
}
