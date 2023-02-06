<?php

namespace Tests\Feature\Livewire\Brands;

use App\Http\Livewire\Brands\Edit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Brand;
use Livewire\Livewire;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class EditTest extends TestCase
{
    /** @test */
    public function edit_brand_component_can_render()
    {
        $this->loginAsAdmin();

        Livewire::test(Edit::class)
                ->assertStatus(200);
    }
    

     /** @test */
     function can_edit_Brand()
     {

        $this->loginAsAdmin();
        
        Livewire::test(Edit::class)
             ->set('name', 'apple')
             ->call('update')
             ->assertHasNoErrors()
             ->assertSee('Successfuly saved!');
  
     }
}
