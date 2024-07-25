<?php

declare(strict_types=1);

use App\Livewire\Warehouses\Index;

test('the livewire warehouse component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('warehouses.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.warehouses.index');
});
