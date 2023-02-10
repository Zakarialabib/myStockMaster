<?php

declare(strict_types=1);

use App\Http\Livewire\Suppliers\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the supplier create if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.suppliers.create');
});

it('tests the create supplier validation rules', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('name', 'John doe')
        ->set('phone', '00000000000')
        ->call('create');

    assertDatabaseHas('suppliers', [
        'name'  => 'John doe',
        'phone' => '00000000000',
    ]);
});

