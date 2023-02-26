<?php

declare(strict_types=1);

use App\Http\Livewire\Brands\Edit;
use Livewire\Livewire;
use App\Models\Brand;

use function Pest\Laravel\assertDatabaseHas;

it('test the brand edit component if working', function () {
    $this->loginAsAdmin();

    Livewire::test(Edit::class)
        ->assertOk()
        ->assertViewIs('livewire.brands.edit');
});

it('tests the brand edit component', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $brand = brand::factory()->create();

    Livewire::test(Edit::class, ['id' => $brand->id])
        ->set('brand.name', 'apple')
        ->set('brand.description', 'some description')
        ->call('update');

    assertDatabaseHas('brands', [
        'name'        => 'apple',
        'description' => 'some description',
    ]);
});

it('tests the update brand component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $brand = Brand::factory()->create();

    Livewire::test(Edit::class, ['id' => $brand->id])
        ->set('brand.name', '')
        ->set('brand.description', '')
        ->call('update')
        ->assertHasErrors(
            ['brand.name' => 'required'],
        );
});
