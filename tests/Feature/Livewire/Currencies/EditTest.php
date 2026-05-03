<?php

declare(strict_types=1);

use App\Livewire\Currency\Edit;
use App\Models\Currency;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the currency edit if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Edit::class)
        ->assertOk()
        ->assertViewIs('livewire.currency.edit');
});

it('tests the update currency can component', function () {
    $this->loginAsAdmin();

    $currency = Currency::factory()->create();

    Livewire::test(Edit::class)
        ->call('openEditModal', $currency->id)
        ->set('name', 'Us Dollar')
        ->set('code', 'USD')
        ->set('locale', '$')
        ->call('update')
        ->assertHasNoErrors();

    assertDatabaseHas('currencies', [
        'name' => 'Us Dollar',
        'code' => 'USD',
        'locale' => '$',
    ]);
});

it('tests the edit user component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $currency = Currency::factory()->create();

    Livewire::test(Edit::class)
        ->call('openEditModal', $currency->id)
        ->set('name', '')
        ->set('code', '')
        ->set('locale', '')
        ->call('update')
        ->assertHasErrors(['name', 'code', 'locale']);
});
