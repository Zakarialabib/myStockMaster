<?php

declare(strict_types=1);

use App\Http\Livewire\Backup\Index;

it('test backup page if can be rendred', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->livewire(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.backup.index');
});

it('can download a backup', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->livewire(Index::class)
        ->call('downloadBackup', 'backup.zip')
        ->assertOk();

    $backups = Storage::allFiles('backup');

    expect($backups)->not()->toBeEmpty();
});

it('can delete a backup', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->livewire(Index::class)
        ->call('delete', 'backup.zip');

    $backups = Storage::allFiles('backup');

    expect($backups)->toBeEmpty();
});
