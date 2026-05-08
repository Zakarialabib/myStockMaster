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
        ->set('name', 'Us Dollar')
        ->set('code', 'USD')
        ->set('locale', '$')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('currencies', [
        'name' => 'Us Dollar',
        'code' => 'USD',
        'locale' => '$',
    ]);
});

it('tests the create user component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('name', '')
        ->set('code', '')
        ->set('locale', '')
        ->call('create')
        ->assertHasErrors(['name', 'code', 'locale']);
});
