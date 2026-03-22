<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
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
            if ( ! EnvironmentService::isDesktop()) {
                return false;
            }

            // Get all tables from online database
            $tables = $this->getOnlineTables();

            foreach ($tables as $table) {
                $this->createTableInDesktop($table);
            }

            Log::info('Desktop database initialized successfully');

            return true;
        } catch (Exception $e) {
            Log::error('Failed to initialize desktop database: '.$e->getMessage());

            return false;
        }
    }

    /** Sync data from online to offline database */
    public function syncToOffline(): bool
    {
        try {
            if ( ! EnvironmentService::isDesktop() || ! EnvironmentService::needsDataSync()) {
                return false;
            }

            $tables = $this->getSyncableTables();

            foreach ($tables as $table) {
                $this->syncTableToOffline($table);
            }

            $this->updateLastSyncTime();
            Log::info('Data synced to offline database successfully');

            return true;
        } catch (Exception $e) {
            Log::error('Failed to sync to offline database: '.$e->getMessage());

            return false;
        }
    }

    /** Sync data from offline to online database */
    public function syncToOnline(): bool
    {
        try {
            if ( ! EnvironmentService::isDesktop() || ! EnvironmentService::needsDataSync()) {
                return false;
            }

            $tables = $this->getSyncableTables();

            foreach ($tables as $table) {
                $this->syncTableToOnline($table);
            }

            Log::info('Data synced to online database successfully');

            return true;
        } catch (Exception $e) {
            Log::error('Failed to sync to online database: '.$e->getMessage());

            return false;
        }
    }

    /** Get tables that should be synchronized */
    protected function getSyncableTables(): array
    {
        return [
            'products',
            'categories',
            'warehouses',
            'customers',
            'suppliers',
            'sales',
            'sale_items',
            'purchases',
            'purchase_items',
            'stock_movements',
            'settings',
        ];
    }

    /** Get all tables from online database */
    protected function getOnlineTables(): array
    {
        try {
            return DB::connection($this->onlineConnection)
                ->getDoctrineSchemaManager()
                ->listTableNames();
        } catch (Exception $e) {
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
            $columns = DB::connection($this->onlineConnection)
                ->getDoctrineSchemaManager()
                ->listTableColumns($table);

            // Create table in desktop database
            Schema::connection($this->offlineConnection)->create($table, function ($tableBuilder) use ($columns) {
                foreach ($columns as $column) {
                    $this->addColumnToTable($tableBuilder, $column);
                }
            });
        } catch (Exception $e) {
            Log::warning("Could not create table {$table} in desktop database: ".$e->getMessage());
        }
    }

    /** Add column to table builder based on doctrine column */
    protected function addColumnToTable($tableBuilder, $column): void
    {
        $name = $column->getName();
        $type = $column->getType()->getName();

        switch ($type) {
            case 'integer':
                $col = $tableBuilder->integer($name);

                break;
            case 'bigint':
                $col = $tableBuilder->bigInteger($name);

                break;
            case 'string':
                $col = $tableBuilder->string($name, $column->getLength() ?? 255);

                break;
            case 'text':
                $col = $tableBuilder->text($name);

                break;
            case 'decimal':
                $col = $tableBuilder->decimal($name, $column->getPrecision() ?? 8, $column->getScale() ?? 2);

                break;
            case 'datetime':
                $col = $tableBuilder->dateTime($name);

                break;
            case 'date':
                $col = $tableBuilder->date($name);

                break;
            case 'boolean':
                $col = $tableBuilder->boolean($name);

                break;
            default:
                $col = $tableBuilder->string($name);
        }

        if ( ! $column->getNotnull()) {
            $col->nullable();
        }

        if ($column->getAutoincrement()) {
            $col->autoIncrement();
        }
    }

    /** Sync specific table to offline database */
    protected function syncTableToOffline(string $table): void
    {
        try {
            // Get last sync time to fetch only modified records
            $lastSync = $this->getLastSyncTime();

            // Get modified records from online database
            $query = DB::connection($this->onlineConnection)
                ->table($table)
                ->where('updated_at', '>', $lastSync);

            // Use chunking to handle large datasets effectively
            $query->chunk(100, function ($records) use ($table) {
                foreach ($records as $record) {
                    $recordArray = (array) $record;

                    // Update or insert record in offline database
                    DB::connection($this->offlineConnection)
                        ->table($table)
                        ->updateOrInsert(
                            ['id' => $record->id],
                            $recordArray
                        );
                }
            });
        } catch (Exception $e) {
            Log::warning("Could not sync table {$table} to offline: ".$e->getMessage());
        }
    }

    /** Sync specific table to online database */
    protected function syncTableToOnline(string $table): void
    {
        try {
            // Get modified records from offline database
            $offlineData = DB::connection($this->offlineConnection)
                ->table($table)
                ->where('updated_at', '>', $this->getLastSyncTime())
                ->get();

            foreach ($offlineData as $record) {
                $recordArray = (array) $record;

                // Try to update existing record, or insert if not exists
                DB::connection($this->onlineConnection)
                    ->table($table)
                    ->updateOrInsert(
                        ['id' => $record->id ?? null],
                        $recordArray
                    );
            }
        } catch (Exception $e) {
            Log::warning("Could not sync table {$table} to online: ".$e->getMessage());
        }
    }

    /** Get last sync time */
    protected function getLastSyncTime(): string
    {
        return cache('desktop_last_sync', now()->subDays(7)->toDateTimeString());
    }

    /** Update last sync time */
    protected function updateLastSyncTime(): void
    {
        cache(['desktop_last_sync' => now()->toDateTimeString()], now()->addDays(30));
    }

    /** Check if online database is available */
    public function isOnlineAvailable(): bool
    {
        try {
            DB::connection($this->onlineConnection)->getPdo();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /** Get sync status */
    public function getSyncStatus(): array
    {
        return [
            'online_available' => $this->isOnlineAvailable(),
            'last_sync'        => $this->getLastSyncTime(),
            'desktop_mode'     => EnvironmentService::isDesktop(),
            'offline_mode'     => EnvironmentService::isOfflineMode(),
            'sync_needed'      => EnvironmentService::needsDataSync(),
        ];
    }
}
