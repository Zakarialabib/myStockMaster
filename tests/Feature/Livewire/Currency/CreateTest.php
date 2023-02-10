<?php

declare(strict_types=1);

use App\Http\Livewire\Currency\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the currency create if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.currency.create');
});

it('tests the create currency validation rules', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('currency.name', 'Us Dollar')
        ->set('currency.code', 'USD')
        ->set('currency.symbol', '$')
        ->set('currency.exchange_rate', '1')
        ->call('create');

    assertDatabaseHas('currencies', [
        'name'          => 'Us Dollar',
        'code'          => 'USD',
        'symbol'        => '$',
        'exchange_rate' => '1',
    ]);
});
