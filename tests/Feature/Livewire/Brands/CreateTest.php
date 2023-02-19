<?php

declare(strict_types=1);

use App\Http\Livewire\Brands\Create;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the brand create component if working', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.brands.create');
});

it('tests the brand create component', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('name', 'apple')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('brands', [
        'name' => 'apple',
    ]);
});

it('tests the create user component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('name', '')
        ->call('create')
        ->assertHasErrors(
            ['name' => 'required'],
        );
});
