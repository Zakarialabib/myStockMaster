<?php

namespace Tests\Feature\Livewire\Brands;

use App\Http\Livewire\Brands\Create;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Brand;
use Spatie\Permission\Models\Role;

class CreateTest extends TestCase
{
    /** @test */
    public function create_brand_component_can_render()
    {
       $user = $this->loginAsAdmin();

        Livewire::test(Create::class)
                ->assertStatus(200);
    }

      /** @test */
      function can_create_Brand()
      {
        $this->loginAsAdmin();

        Livewire::test(Create::class)
              ->set('name', 'apple')
              ->call('create')
              ->assertHasNoErrors()
              ->assertSee('Successfuly saved!');
   
      }
      
}
