<?php

declare(strict_types=1);

use App\Livewire\Brands\Edit;
use Livewire\Livewire;
use App\Models\Brand;

it('test the brand edit component if working', function () {
    $this->loginAsAdmin();

    Livewire::test(Edit::class)
        ->assertOk()
        ->assertViewIs('livewire.brands.edit');
});

it('tests the brand edit component', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $brand = Brand::factory()->create();

    Livewire::test(Edit::class, ['id' => $brand->id])
        ->set('brand.name', 'apple')
        ->set('brand.description', 'some description')
        ->call('update')
        ->assertHasNoErrors();

    $brand = Brand::find(1);
    expect($brand->name)->toBe('apple');
    expect($brand->description)->toBe('some description');
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
