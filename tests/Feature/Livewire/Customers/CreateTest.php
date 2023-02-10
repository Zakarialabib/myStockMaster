<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Customer;

use App\Http\Livewire\Customers\Create;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function create_customer_component_can_render()
    {
        $this->withoutExceptionHandling();

        $this->loginAsAdmin();

        Livewire::test(Create::class)
            ->assertOk()
            ->assertViewIs('livewire.customers.create');
    }

      /** @test */
      public function can_create_customer()
      {
          $this->loginAsAdmin();

          Livewire::test(Create::class)
              ->set('name', 'apple')
              ->call('create');

          $this->assertDatabaseHas('customers', [
              'name' => 'apple',
          ]);
      }
}
