<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Category;

use App\Http\Livewire\Categories\Create;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function create_brand_component_can_render()
    {
        $this->withoutExceptionHandling();
        $this->loginAsAdmin();

        Livewire::test(Create::class)
            ->assertOk()
            ->assertViewIs('livewire.categories.create');
    }

      /** @test */
      public function can_create_brand()
      {
          $this->loginAsAdmin();

          Livewire::test(Create::class)
              ->set('name', 'apple')
              ->call('create');

          $this->assertDatabaseHas('categories', [
              'name' => 'apple',
          ]);
      }
}
