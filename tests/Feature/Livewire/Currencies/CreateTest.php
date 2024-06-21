<?php

declare(strict_types=1);

use App\Livewire\Currency\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the currency create if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.currency.create');
});

it('tests the create currency can create', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('currency.name', 'Us Dollar')
        ->set('currency.code', 'USD')
        ->set('currency.locale', '$')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('currencies', [
        'name'   => 'Us Dollar',
        'code'   => 'MA',
        'locale' => '$',
    ]);
});

it('tests the create user component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('currency.name', '')
        ->set('currency.code', '')
        ->set('currency.locale', '')
        ->call('create')
        ->assertHasErrors(
            ['currency.name' => 'required'],
            ['currency.code'   => 'required'],
            ['currency.locale' => 'required'],
        );
});
