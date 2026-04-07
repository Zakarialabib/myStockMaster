<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DatabaseSyncService
{
    protected string $onlineConnection = 'mysql';

    protected string $offlineConnection = 'sqlite_desktop';

    /** Initialize desktop database with schema from online database */
    public function initializeDesktopDatabase(): bool
    {
        try {
            if (! EnvironmentService::isDesktop()) {
                return false;
            }

            // Get all tables from online database
            $tables = $this->getOnlineTables();

            foreach ($tables as $table) {
                $this->createTableInDesktop($table);
            }

            Log::info('Desktop database initialized successfully');

            return true;
        } catch (Exception $exception) {
            Log::error('Failed to initialize desktop database: ' . $exception->getMessage());

            return false;
        }
    }

    /** Sync data from online to offline database */
    public function syncToOffline(): bool
    {
        try {
            if (! EnvironmentService::isDesktop() || ! $this->isOnlineAvailable()) {
                return false;
            }

            $tables = $this->getSyncableTables();

            foreach ($tables as $table) {
                $this->syncTableToOffline($table);
            }

            $this->updateLastSyncTime();
            Log::info('Data synced to offline database successfully');

            return true;
        } catch (Exception $exception) {
            Log::error('Failed to sync to offline database: ' . $exception->getMessage());

            return false;
        }
    }

    /** Sync data from offline to online database */
    public function syncToOnline(): bool
    {
        try {
            if (! EnvironmentService::isDesktop() || ! $this->isOnlineAvailable()) {
                return false;
            }

            $tables = $this->getSyncableTables();

            foreach ($tables as $table) {
                $this->syncTableToOnline($table);
            }

            Log::info('Data synced to online database successfully');

            return true;
        } catch (Exception $exception) {
            Log::error('Failed to sync to online database: ' . $exception->getMessage());

            return false;
        }
    }

    /** Get tables that should be synchronized */
    protected function getSyncableTables(): array
    {
        return [
            'brands',
            'categories',
            'customers',
            'movements',
            'products',
            'purchase_details',
            'purchase_payments',
            'purchase_return_details',
            'purchase_return_payments',
            'purchase_returns',
            'purchases',
            'sale_details',
            'sale_payments',
            'sale_return_details',
            'sale_return_payments',
            'sale_returns',
            'sales',
            'settings',
            'suppliers',
            'transfers',
            'warehouses',
        ];
    }

    /** Get all tables from online database */
    protected function getOnlineTables(): array
    {
        try {
            $tables = Schema::connection($this->onlineConnection)->getTables();

            return array_column($tables, 'name');
        } catch (Exception) {
            // Fallback to basic table list if online is not available
            return $this->getSyncableTables();
        }
    }

    /** Create table structure in desktop database */
    protected function createTableInDesktop(string $table): void
    {
        try {
            // Check if table already exists in desktop database
            if (Schema::connection($this->offlineConnection)->hasTable($table)) {
                return;
            }

            // Get table schema from online database
            $columns = Schema::connection($this->onlineConnection)->getColumns($table);

            // Create table in desktop database
            Schema::connection($this->offlineConnection)->create($table, function ($tableBuilder) use ($columns): void {
                foreach ($columns as $column) {
                    $this->addColumnToTable($tableBuilder, (object) $column);
                }
            });
        } catch (Exception $exception) {
            if (app()->runningUnitTests()) {
            }

            Log::warning(sprintf('Could not create table %s in desktop database: ', $table) . $exception->getMessage());
        }
    }

    /** Add column to table builder based on doctrine column */
    protected function addColumnToTable($tableBuilder, $column): void
    {
        $name = $column->name;
        $type = strtolower((string) $column->type_name);

        $col = match ($type) {
            'integer', 'int' => $tableBuilder->integer($name),
            'bigint' => $tableBuilder->bigInteger($name),
            // Laravel 11 Schema::getColumns doesn't always expose length directly, default to 255
            'varchar', 'string' => $tableBuilder->string($name),
            'text' => $tableBuilder->text($name),
            'decimal', 'numeric' => $tableBuilder->decimal($name, 8, 2),
            'datetime', 'timestamp' => $tableBuilder->dateTime($name),
            'date' => $tableBuilder->date($name),
            'boolean', 'tinyint' => $tableBuilder->boolean($name),
            default => $tableBuilder->string($name),
        };

        if ($column->nullable) {
            $col->nullable();
        }

        if ($column->auto_increment) {
            $col->autoIncrement();
        }
    }

    /** Sync specific table to offline database */
    protected function syncTableToOffline(string $table): void
    {
        try {
            if (! Schema::connection($this->onlineConnection)->hasTable($table)
                || ! Schema::connection($this->offlineConnection)->hasTable($table)) {
                return;
            }

            $lastSync = $this->getLastSyncTime();
            $query = DB::connection($this->onlineConnection)->table($table);
            $hasUpdatedAt = Schema::connection($this->onlineConnection)->hasColumn($table, 'updated_at');

            if ($hasUpdatedAt && $lastSync) {
                $query->where('updated_at', '>', $lastSync);
            }

            if (app()->runningUnitTests() && $table === 'categories') {
            }

            $query->chunk(100, function ($records) use ($table, $hasUpdatedAt): void {
                foreach ($records as $record) {
                    $recordArray = (array) $record;
                    $id = $recordArray['id'] ?? null;

                    if ($id === null) {
                        continue;
                    }

                    // Conflict resolution: Last-Write-Wins based on updated_at
                    if ($hasUpdatedAt) {
                        $existingOffline = DB::connection($this->offlineConnection)
                            ->table($table)
                            ->where('id', $id)
                            ->first();

                        if ($existingOffline && isset($existingOffline->updated_at)) {
                            $offlineTime = strtotime((string) $existingOffline->updated_at);
                            $onlineTime = strtotime((string) $recordArray['updated_at']);

                            // If offline is newer, skip overwriting from online
                            if ($offlineTime > $onlineTime) {
                                continue;
                            }
                        }
                    }

                    try {
                        DB::connection($this->offlineConnection)
                            ->table($table)
                            ->updateOrInsert(
                                ['id' => $id],
                                $recordArray
                            );
                    } catch (Exception $e) {
                        if (app()->runningUnitTests()) {
                        }

                        throw $e;
                    }
                }
            });
        } catch (Exception $exception) {
            if (app()->runningUnitTests()) {
            }

            Log::warning(sprintf('Could not sync table %s to offline: ', $table) . $exception->getMessage());
        }
    }

    /** Sync specific table to online database */
    protected function syncTableToOnline(string $table): void
    {
        try {
            if (! Schema::connection($this->onlineConnection)->hasTable($table)
                || ! Schema::connection($this->offlineConnection)->hasTable($table)) {
                return;
            }

            $lastSync = $this->getLastSyncTime();
            $query = DB::connection($this->offlineConnection)->table($table);
            $hasUpdatedAt = Schema::connection($this->offlineConnection)->hasColumn($table, 'updated_at');

            if ($hasUpdatedAt && $lastSync) {
                $query->where('updated_at', '>', $lastSync);
            }

            $offlineData = $query->get();

            foreach ($offlineData as $record) {
                $recordArray = (array) $record;
                $id = $recordArray['id'] ?? null;

                if ($id === null) {
                    continue;
                }

                // Conflict resolution: Last-Write-Wins based on updated_at
                if ($hasUpdatedAt) {
                    $existingOnline = DB::connection($this->onlineConnection)
                        ->table($table)
                        ->where('id', $id)
                        ->first();

                    if ($existingOnline && isset($existingOnline->updated_at)) {
                        $onlineTime = strtotime($existingOnline->updated_at);
                        $offlineTime = strtotime((string) $recordArray['updated_at']);

                        // If online is newer, skip overwriting from offline
                        if ($onlineTime > $offlineTime) {
                            continue;
                        }
                    }
                }

                DB::connection($this->onlineConnection)
                    ->table($table)
                    ->updateOrInsert(
                        ['id' => $id],
                        $recordArray
                    );
            }
        } catch (Exception $exception) {
            Log::warning(sprintf('Could not sync table %s to online: ', $table) . $exception->getMessage());
        }
    }

    /** Get last sync time */
    protected function getLastSyncTime(): string
    {
        return (string) Cache::get('database_sync.last_sync', now()->subDays(7)->toDateTimeString());
    }

    /** Update last sync time */
    protected function updateLastSyncTime(): void
    {
        Cache::put('database_sync.last_sync', now()->toDateTimeString(), now()->addDays(30));
    }

    /** Check if online database is available */
    public function isOnlineAvailable(): bool
    {
        try {
            DB::connection($this->onlineConnection)->getPdo();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    /** Get sync status */
    public function getSyncStatus(): array
    {
        return [
            'online_available' => $this->isOnlineAvailable(),
            'last_sync' => $this->getLastSyncTime(),
            'desktop_mode' => EnvironmentService::isDesktop(),
            'offline_mode' => EnvironmentService::isOfflineMode(),
            'sync_needed' => EnvironmentService::needsDataSync(),
        ];
    }
}
