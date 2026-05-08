<?php

declare(strict_types=1);

use App\Livewire\Brands\Edit;
use App\Models\Brand;
use Livewire\Livewire;

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

    Livewire::test(Edit::class)
        ->call('openEditModal', $brand->id)
        ->set('form.name', 'apple')
        ->set('form.description', 'some description')
        ->call('update')
        ->assertHasNoErrors();

    $brand->refresh();
    expect($brand->name)->toBe('apple');
    expect($brand->description)->toBe('some description');
});

it('tests the update brand component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $brand = Brand::factory()->create();

    Livewire::test(Edit::class)
        ->call('openEditModal', $brand->id)
        ->set('form.name', '')
        ->call('update')
        ->assertHasErrors(['form.name']);
});
