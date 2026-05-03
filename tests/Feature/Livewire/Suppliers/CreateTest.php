<?php

declare(strict_types=1);

use App\Livewire\Suppliers\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the supplier create component if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.suppliers.create');
});

it('tests the create supplier component', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->call('openModal')
        ->set('form.name', 'John doe')
        ->set('form.phone', '00000000000')
        ->set('form.email', 'supplier@gmail.com')
        ->set('form.city', 'casablanca')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('suppliers', [
        'name' => 'John doe',
        'phone' => '00000000000',
        'email' => 'supplier@gmail.com',
        'city' => 'casablanca',
    ]);
});

it('tests the create supplier component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->call('openModal')
        ->set('form.name', '')
        ->set('form.phone', '')
        ->call('create')
        ->assertHasErrors(['form.name', 'form.phone']);
});
