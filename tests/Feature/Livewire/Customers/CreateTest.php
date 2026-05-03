<?php

declare(strict_types=1);

use App\Livewire\Customers\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the customers create if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.customers.create');
});

it('tests the create customer validation rules', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->call('openCreateModal')
        ->set('form.name', 'John doe')
        ->set('form.phone', '00000000000')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('customers', [
        'name' => 'John doe',
        'phone' => '00000000000',
    ]);
});

it('tests the create customer component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->call('openCreateModal')
        ->set('form.name', '')
        ->set('form.phone', '')
        ->call('create')
        ->assertHasErrors(['form.name', 'form.phone']);
});
