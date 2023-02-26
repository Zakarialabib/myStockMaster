<?php

declare(strict_types=1);

use App\Http\Livewire\Suppliers\Create;
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
        ->set('supplier.name', 'John doe')
        ->set('supplier.phone', '00000000000')
        ->set('supplier.email', 'supplier@gmail.com')
        ->set('supplier.city', 'casablanca')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('suppliers', [
        'name'  => 'John doe',
        'phone' => '00000000000',
        'email' => 'supplier@gmail.com',
        'city'  => 'casablanca',
    ]);
});

it('tests the create supplier component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('supplier.name', '')
        ->set('supplier.phone', '')
        ->set('supplier.email', '')
        ->set('supplier.city', '')
        ->call('create')
        ->assertHasErrors(
            ['supplier.name' => 'required'],
            ['supplier.phonne' => 'required']
        );
});
