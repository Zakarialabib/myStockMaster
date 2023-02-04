<?php

namespace Tests\Feature\Livewire\Categories;

use App\Http\Livewire\Categories\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class IndexTest extends TestCase
{
    /** @test */
    public function index_categories_component_can_render()
    {
        $this->withoutExceptionHandling();
        $this->loginAsAdmin();

        Livewire::test(Index::class)
                ->assertStatus(200);

    }
}
