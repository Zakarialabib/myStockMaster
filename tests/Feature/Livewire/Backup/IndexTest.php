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
