<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Currency;

use App\Http\Livewire\Customers\Create;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function create_currency_component_can_render()
    {
        $this->withoutExceptionHandling();

        $this->loginAsAdmin();

        Livewire::test(Create::class)
            ->assertOk()
            ->assertViewIs('livewire.currency.create');
    }

      /** @test */
      public function can_create_currency()
      {
          $this->loginAsAdmin();

          Livewire::test(Create::class)
              ->set('name', 'apple')
              ->call('create');

          $this->assertDatabaseHas('currencies', [
              'name' => 'apple',
          ]);
      }
}
