<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Expense;

use App\Http\Livewire\Expense\Index;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function expense_index_component_can_render()
    {
        $this->withoutExceptionHandling();

        $this->loginAsAdmin();

        Livewire::test(Index::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.expense.create');
    }
}
