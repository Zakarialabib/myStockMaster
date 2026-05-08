<?php

declare(strict_types=1);

use App\Livewire\Brands\Create;
use App\Models\Brand;
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
        ->set('form.name', 'apple')
        ->set('form.description', 'Apple description')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('brands', [
        'name' => 'apple',
        'description' => 'Apple description',
    ]);
});

it('tests the create brand component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('form.name', '')
        ->call('create')
        ->assertHasErrors(['form.name']);
});

it('throws an error if the brand name is duplicated', function () {
    $this->loginAsAdmin();

    Brand::query()->create([
        'name' => 'apple',
    ]);

    Livewire::test(Create::class)
        ->set('form.name', 'apple')
        ->call('create')
        ->assertHasErrors(['form.name']);
});
