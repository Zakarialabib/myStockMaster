<?php

declare(strict_types=1);

use App\Http\Livewire\Expense\Create;
use App\Models\ExpenseCategory;

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

    $category_id = $category->id;

    Livewire::test(Create::class)
        ->set('reference', '12345')
        ->set('date', '01-01-2023')
        ->set('category_id', $category_id)
        ->set('amount', '50000')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas('expenses', [
        'reference'   => '12345',
        'date'        => '01-01-2023',
        'category_id' => $category_id,
        'amount'      => '50000',
    ]);
});

it('tests the create expense component validation', function () {
    $this->loginAsAdmin();

    Livewire::test(Create::class)
        ->set('reference', '')
        ->set('date', '')
        ->set('category_id', '')
        ->set('amount', '')
        ->call('create')
        ->assertHasErrors(
            ['reference' => 'required'],
            ['date'        => 'required'],
            ['category_id' => 'required'],
            ['amount'      => 'required'],
        );
});
