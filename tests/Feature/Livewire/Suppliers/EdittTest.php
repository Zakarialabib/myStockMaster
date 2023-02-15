<?php

declare(strict_types=1);

use App\Http\Livewire\Suppliers\Edit;
use App\Models\Supplier;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the suppliers edit component if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Edit::class)
        ->assertOk()
        ->assertViewIs('livewire.suppliers.edit');
});

it('tests the Update supplier component', function () {
    $this->loginAsAdmin();

    $supplier = Supplier::factory()->create();

    Livewire::test(Edit::class, ['id' => $supplier->id])
        ->set('supplier.name', 'John doe')
        ->set('supplier.phone', '00000000000')
        ->set('supplier.email', 'supplier@gmail.com')
        ->set('supplier.city', 'casablanca')
        ->call('update')
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

    $supplier = Supplier::factory()->create();

    Livewire::test(Edit::class, ['id' => $supplier->id])
        ->set('supplier.name', '')
        ->set('supplier.phone', '')
        ->set('supplier.email', '')
        ->set('supplier.city', '')
        ->call('create')
        ->assertHasErrors(
            ['supplier.name' => 'required'],
            ['supplier.phonne' => 'required'],
            ['supplier.email'  => 'nullable'],
            ['supplier.city'   => 'nullable'],
        );
});
