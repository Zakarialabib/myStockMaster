<?php

declare(strict_types=1);

use App\Http\Livewire\Categories\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the category create if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.categories.create');
});

it('tests the create category validation rules', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('name', 'apple')
        ->call('create');

    assertDatabaseHas('categories', [
        'name' => 'apple',
    ]);
});
