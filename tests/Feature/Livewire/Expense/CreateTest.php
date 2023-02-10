<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Expense;

use App\Http\Livewire\Expense\Create;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function create_expense_component_can_render()
    {
        $this->withoutExceptionHandling();

        $this->loginAsAdmin();

        Livewire::test(Create::class)
            ->assertOk()
            ->assertViewIs('livewire.expense.create');
    }

      /** @test */
      public function can_create_expense()
      {
          $this->loginAsAdmin();

          Livewire::test(Create::class)
              ->set('name', 'apple')
              ->call('create');

          $this->assertDatabaseHas('expenses', [
              'name' => 'apple',
          ]);
      }
}
