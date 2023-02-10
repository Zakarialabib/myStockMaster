<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Category;

use App\Http\Livewire\Categories\Edit;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    /** @test */
    public function edit_category_component_can_render()
    {
        $this->withoutExceptionHandling();

        $this->loginAsAdmin();

        Livewire::test(Edit::class)
            ->assertOk()
            ->assertViewIs('livewire.categories.edit');
    }

     /** @test */
     public function can_edit_Category()
     {
         $this->loginAsAdmin();

         $category = Category::create([
             'name' => 'Apple',
             'code' => Str::random(5),
         ]);

         Livewire::test(Edit::class, ['category_id' => $category->id])
             ->set('name', 'Apple')
             ->set('code', 'Apple')
             ->call('update');

         $this->assertDatabaseMissing('categories', [
             'name' => 'Apple',
             'code' => 'Apple',
         ]);
     }
}
