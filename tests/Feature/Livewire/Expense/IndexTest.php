<?php

namespace Tests\Feature\Livewire\Expense;

use App\Http\Livewire\Expense\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
                ->assertStatus(200);

    }
}
