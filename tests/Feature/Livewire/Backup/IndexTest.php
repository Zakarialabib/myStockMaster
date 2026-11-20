<?php

declare(strict_types=1);

use App\Livewire\Backup\Index;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('test backup page if can be rendred', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.backup.index');
});

it('can download a backup', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Storage::put('backup.zip', 'test backup body');

    Livewire::test(Index::class)
        ->call('downloadBackup', 'backup.zip')
        ->assertOk();
});

it('can delete a backup', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Storage::put('backup.zip', 'test backup body');

    Livewire::test(Index::class)
        ->call('delete', 'backup.zip');

    expect(Storage::exists('backup.zip'))->toBeFalse();
});

it('only lists files from the configured backup directory', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    config(['backup.backup.name' => 'stock-backups']);

    Storage::fake();
    Storage::put('stock-backups/2026-01-01.zip', 'test backup body');
    Storage::put('unrelated/file.txt', 'not a backup');

    Livewire::test(Index::class)
        ->assertViewHas('backups', ['stock-backups/2026-01-01.zip']);
});
