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
