<?php

declare(strict_types=1);

use App\Http\Livewire\ExpenseCategories\Index;

test('the livewire expense component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('expense-categories.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.expense-categories.index');
});
