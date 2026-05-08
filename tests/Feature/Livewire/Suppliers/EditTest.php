<?php

declare(strict_types=1);

use App\Livewire\Suppliers\Edit;
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

it('updates a supplier', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $supplier = Supplier::factory()->create();

    Livewire::test(Edit::class)
        ->call('openModal', $supplier->id)
        ->set('form.name', 'New Name')
        ->set('form.phone', '00000000000')
        ->set('form.email', 'supplier@gmail.com')
        ->set('form.city', 'casablanca')
        ->call('update')
        ->assertHasNoErrors();

    assertDatabaseHas('suppliers', [
        'id' => $supplier->id,
        'name' => 'New Name',
        'phone' => '00000000000',
        'email' => 'supplier@gmail.com',
        'city' => 'casablanca',
    ]);
});

it('tests the update supplier component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $supplier = Supplier::factory()->create();

    Livewire::test(Edit::class)
        ->call('openModal', $supplier->id)
        ->set('form.name', '')
        ->set('form.phone', '')
        ->call('update')
        ->assertHasErrors(['form.name', 'form.phone']);
});

it('validates the supplier', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $supplier = Supplier::factory()->create();

    Livewire::test(Edit::class)
        ->call('openModal', $supplier->id)
        ->set('form.name', '')
        ->set('form.phone', '')
        ->call('update')
        ->assertHasErrors(['form.name', 'form.phone']);
});
