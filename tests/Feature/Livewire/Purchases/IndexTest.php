<?php

declare(strict_types=1);

use App\Livewire\Purchase\Index;
use Livewire\Livewire;

it('test purchases list if can be rendred', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.purchase.index');
});
