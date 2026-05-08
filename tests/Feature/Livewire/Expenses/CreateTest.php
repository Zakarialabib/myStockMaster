<?php

declare(strict_types=1);

use App\Livewire\Expense\Create;
use App\Models\ExpenseCategory;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('test the expenses create if working', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->assertOk()
        ->assertViewIs('livewire.expense.create');
});

it('tests the create expense can create', function () {
    $this->loginAsAdmin();

    $category = ExpenseCategory::factory()->create();

    Livewire::test(Create::class)
        ->call('openCreateModal')
        ->set('form.reference', '12345')
        ->set('form.date', '2023-01-01')
        ->set('form.category_id', $category->id)
        ->set('form.amount', '50000')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('expenses', [
        'category_id' => $category->id,
        'amount' => 5_000_000,
    ]);
});

it('tests the create expense component validation', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->call('openCreateModal')
        ->set('form.reference', '')
        ->set('form.date', '')
        ->set('form.category_id', '')
        ->set('form.amount', '')
        ->call('create')
        ->assertHasErrors(['form.reference', 'form.date', 'form.category_id', 'form.amount']);
});
