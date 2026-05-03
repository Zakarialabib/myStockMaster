<?php

declare(strict_types=1);

use App\Livewire\Customers\Edit;
use App\Models\Customer;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the customers update component if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Edit::class)
        ->assertOk()
        ->assertViewIs('livewire.customers.edit');
});

it('tests the update customer component', function () {
    $this->loginAsAdmin();

    $customer = Customer::factory()->create();

    Livewire::test(Edit::class)
        ->call('openEditModal', $customer->id)
        ->set('form.name', 'John doe')
        ->set('form.phone', '00000000000')
        ->call('update')
        ->assertHasNoErrors();

    assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'John doe',
        'phone' => '00000000000',
    ]);
});

it('tests the edit customer component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $customer = Customer::factory()->create();

    Livewire::test(Edit::class)
        ->call('openEditModal', $customer->id)
        ->set('form.name', '')
        ->set('form.phone', '')
        ->call('update')
        ->assertHasErrors(['form.name', 'form.phone']);
});
