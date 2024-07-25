<?php

declare(strict_types=1);

use App\Livewire\Currency\Edit;
use Livewire\Livewire;
use App\Models\Currency;

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

    Livewire::test(Edit::class, ['id' => $currency->id])
        ->set('currency.name', 'Us Dollar')
        ->set('currency.code', 'USD')
        ->set('currency.locale', '$')
        ->call('update')
        ->assertHasNoErrors();

    assertDatabaseHas('currencies', [
        'name'   => 'Us Dollar',
        'code'   => 'USD',
        'locale' => '$',
    ]);
});

it('tests the edit user component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $currency = Currency::factory()->create();

    Livewire::test(Edit::class, ['id' => $currency->id])
        ->set('currency.name', '')
        ->set('currency.code', '')
        ->set('currency.locale', '')
        ->call('update')
        ->assertHasErrors(
            ['currency.name' => 'required'],
            ['currency.code'   => 'required'],
            ['currency.locale' => 'required'],
        );
});
