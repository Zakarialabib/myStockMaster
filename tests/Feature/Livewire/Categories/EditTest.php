<?php

declare(strict_types=1);

use App\Livewire\Categories\Edit;
use App\Models\Category;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the category edit component if working', function () {
    $this->loginAsAdmin();

    Livewire::test(Edit::class)
        ->assertOk()
        ->assertViewIs('livewire.categories.edit');
});

it('tests the update category component', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $category = Category::factory()->create();

    Livewire::test(Edit::class)
        ->call('openModal', $category->id)
        ->set('form.name', 'Electronics')
        ->set('form.code', '123456')
        ->call('update');

    assertDatabaseHas('categories', [
        'name' => 'Electronics',
        'code' => '123456',
    ]);
});

it('tests the category update component validation', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $category = Category::factory()->create();

    Livewire::test(Edit::class)
        ->call('openModal', $category->id)
        ->set('form.name', '')
        ->call('update')
        ->assertHasErrors(['form.name']);
});
