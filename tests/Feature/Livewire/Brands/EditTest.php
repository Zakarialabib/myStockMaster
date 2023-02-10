<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Brands;

use App\Http\Livewire\Brands\Edit;
use App\Models\Brand;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function edit_brand_component_can_render()
    {
        $this->loginAsAdmin();

        Livewire::test(Edit::class)
            ->assertOk()
            ->assertViewIs('livewire.brands.edit');
    }

     /** @test */
     public function can_edit_Brand()
     {
         $this->loginAsAdmin();

         $brand = Brand::create([
             'name' => 'Apple',
         ]);

         Livewire::test(Edit::class, ['brand_id' => $brand->id])
             ->set('name', 'Apple')
             ->call('update');

         $this->assertDatabaseMissing('brands', [
             'name' => 'Apple',
         ]);
     }
}
