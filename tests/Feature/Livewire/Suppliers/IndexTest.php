<?php

declare(strict_types=1);

use App\Http\Livewire\Suppliers\Index;

it('test suppliers list if can be rendred', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->livewire(Index::class)
        ->assertOk()
        ->assertViewIs('livewire.suppliers.index');
});
