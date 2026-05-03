<?php

declare(strict_types=1);

use App\Livewire\Suppliers\Index;
use Livewire\Livewire;

it('test suppliers list if can be rendred', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.suppliers.index');
});
