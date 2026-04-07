<?php

declare(strict_types=1);

use App\Services\DatabaseSyncService;
use App\Services\EnvironmentService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class);

beforeEach(function () {
    $testDbPath = database_path('database-test.sqlite');
    if (! file_exists($testDbPath)) {
        touch($testDbPath);
    }

    // Override online connection to use default sqlite connection used by tests
    Config::set('database.connections.mysql', [
        'driver' => 'sqlite',
        'database' => $testDbPath,
        'prefix' => '',
    ]);

    // Setup offline database (memory)
    Config::set('database.connections.sqlite_desktop', [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ]);

    // Create minimal schema for testing on online DB to avoid Spatie migration issues
    // We only create the table in online DB so initializeDesktopDatabase can copy it
    if (! Schema::connection('mysql')->hasTable('categories')) {
        Schema::connection('mysql')->create('categories', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->timestamps();
        });
    } else {
        DB::connection('mysql')->table('categories')->truncate();
    }

    // Drop from offline DB to start fresh
    Schema::connection('sqlite_desktop')->dropAllTables();

    // Force environment to desktop
    EnvironmentService::clearDesktopCache();
    $_SERVER['NATIVEPHP_RUNNING'] = true;
    putenv('DESKTOP_MODE=true');
    Config::set('app.env', 'desktop');
});

it('initializes desktop database from online schema', function () {
    $syncService = new DatabaseSyncService;

    // Drop categories table to test creation
    Schema::connection('sqlite_desktop')->dropIfExists('categories');

    // Act
    $result = $syncService->initializeDesktopDatabase();

    if (! $result) {
        dump('Failed to init. isDesktop: ' . (EnvironmentService::isDesktop() ? 'true' : 'false'));
    }

    // Assert
    expect($result)->toBeTrue();
    expect(Schema::connection('sqlite_desktop')->hasTable('categories'))->toBeTrue();
});

it('syncs data to offline database', function () {
    $syncService = new DatabaseSyncService;
    $syncService->initializeDesktopDatabase();

    // Insert data into online DB
    DB::connection('mysql')->table('categories')->insert([
        'id' => 1,
        'name' => 'Online Category',
        'code' => 'C001',
        'updated_at' => now(),
        'created_at' => now(),
    ]);

    // Act
    Cache::flush();
    $result = $syncService->syncToOffline();

    // Assert
    expect($result)->toBeTrue();

    dump(DB::connection('sqlite_desktop')->table('categories')->get());

    $offlineCategory = DB::connection('sqlite_desktop')->table('categories')->find(1);
    expect($offlineCategory)->not->toBeNull();
    expect($offlineCategory->name)->toBe('Online Category');
});

it('syncs data to online database', function () {
    $syncService = new DatabaseSyncService;
    $syncService->initializeDesktopDatabase();

    // Insert data into offline DB
    DB::connection('sqlite_desktop')->table('categories')->insert([
        'id' => 1,
        'name' => 'Offline Category',
        'code' => 'C002',
        'updated_at' => now(),
        'created_at' => now(),
    ]);

    // Act
    Cache::flush();
    $result = $syncService->syncToOnline();

    // Assert
    expect($result)->toBeTrue();

    $onlineCategory = DB::connection('mysql')->table('categories')->find(1);
    expect($onlineCategory)->not->toBeNull();
    expect($onlineCategory->name)->toBe('Offline Category');
});

it('resolves conflicts using last-write-wins', function () {
    $syncService = new DatabaseSyncService;
    $syncService->initializeDesktopDatabase();

    $olderTime = now()->subMinutes(10);
    $newerTime = now();

    // Insert data into online DB (Newer)
    DB::connection('mysql')->table('categories')->insert([
        'id' => 1,
        'name' => 'Newer Online Category',
        'code' => 'C001',
        'updated_at' => $newerTime,
        'created_at' => $olderTime,
    ]);

    // Insert data into offline DB (Older)
    DB::connection('sqlite_desktop')->table('categories')->insert([
        'id' => 1,
        'name' => 'Older Offline Category',
        'code' => 'C001',
        'updated_at' => $olderTime,
        'created_at' => $olderTime,
    ]);

    // Act - Sync from online to offline
    $syncService->syncToOffline();

    // Assert - Offline should have the newer online category
    $offlineCategory = DB::connection('sqlite_desktop')->table('categories')->find(1);
    expect($offlineCategory->name)->toBe('Newer Online Category');

    // Reset data
    DB::connection('mysql')->table('categories')->truncate();
    DB::connection('sqlite_desktop')->table('categories')->truncate();

    // Insert data into online DB (Older)
    DB::connection('mysql')->table('categories')->insert([
        'id' => 2,
        'name' => 'Older Online Category',
        'code' => 'C002',
        'updated_at' => $olderTime,
        'created_at' => $olderTime,
    ]);

    // Insert data into offline DB (Newer)
    DB::connection('sqlite_desktop')->table('categories')->insert([
        'id' => 2,
        'name' => 'Newer Offline Category',
        'code' => 'C002',
        'updated_at' => $newerTime,
        'created_at' => $olderTime,
    ]);

    // Act - Sync from offline to online
    $syncService->syncToOnline();

    // Assert - Online should have the newer offline category
    $onlineCategory = DB::connection('mysql')->table('categories')->find(2);
    expect($onlineCategory->name)->toBe('Newer Offline Category');
});
