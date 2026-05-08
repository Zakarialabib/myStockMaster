<?php

declare(strict_types=1);

use App\Livewire\Customers\Index;
use Livewire\Livewire;

test('the livewire customers component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('customers.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.customers.index');
});
