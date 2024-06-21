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

    Livewire::test(Edit::class, ['id' => $customer->id])
        ->set('customer.name', $customer->name)
        ->set('customer.phone', $customer->phone)
        ->call('update')
        ->assertHasNoErrors();

    assertDatabaseHas('customers', [
        'name'  => 'John doe',
        'phone' => '00000000000',
    ]);
});

it('tests the edit customer component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $customer = Customer::factory()->create();

    Livewire::test(Edit::class, ['id' => $customer->id])
        ->set('customer.name', '')
        ->set('customer.phone', '')
        ->call('update')
        ->assertHasErrors(
            ['customer.name' => 'required'],
            ['customer.phone' => 'required'],
        );
});
