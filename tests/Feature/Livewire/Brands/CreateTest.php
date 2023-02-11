<?php

declare(strict_types=1);

use App\Http\Livewire\Brands\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the brand create if working', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.brands.create');
});

it('tests the Create brand validation rules', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('name', 'apple')
        ->call('create');

    assertDatabaseHas('brands', [
        'name' => 'apple',
    ]);
});
