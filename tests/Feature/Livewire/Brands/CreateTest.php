<?php

declare(strict_types=1);

use App\Http\Livewire\Brands\Create;
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
        ->set('brand.name', 'apple')
        ->set('brand.description', 'Apple description')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('brands', [
        'name'        => 'apple',
        'description' => 'some description',
    ]);
});

it('tests the create brand component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('brand.name', '')
        ->call('create')
        ->assertHasErrors(
            ['brand.name' => 'required'],
        );
});

it('throws an error if the brand name is duplicated', function () {
    $this->loginAsAdmin();

    Brand::create([
        'name' => 'apple',
    ]);

    Livewire::test(Create::class)
        ->set('brand.name', 'apple')
        ->call('create')
        ->assertHasErrors(
            ['name' => 'The brand name has already been taken.'],
        );
});
