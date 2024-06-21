<?php

declare(strict_types=1);

use App\Livewire\Expense\Index;

test('the livewire expense component can be viewed', function () {
    $this->withoutExceptionHandling();
    $this->loginAsAdmin();

    $this->get(route('expenses.index'))
        ->assertStatus(200);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.expense.index');
});
