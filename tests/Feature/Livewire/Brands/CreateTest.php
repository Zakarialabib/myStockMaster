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
        ->set('brand.name', 'apple')
        ->set('brand.description', faker()->realText(120))
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('brands', [
        'name' => 'apple',
        'description' => 'some description',
    ]);
});

it('tests the create brand component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('brand.name', '')
        ->set('brand.description', '')
        ->call('create')
        ->assertHasErrors(
            ['brand.name' => 'required'],
        );
});
