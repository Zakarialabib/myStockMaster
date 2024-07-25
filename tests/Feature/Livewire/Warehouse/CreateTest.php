<?php

declare(strict_types=1);

use App\Livewire\Warehouses\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the warehouse create if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.warehouses.create');
});

it('tests the create warehouse validation rules', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('warehouse.name', 'apple')
        ->set('warehouse.phone', '00000000000')
        ->call('create');

    assertDatabaseHas('warehouses', [
        'name'  => 'apple',
        'phone' => '00000000000',
    ]);
});
