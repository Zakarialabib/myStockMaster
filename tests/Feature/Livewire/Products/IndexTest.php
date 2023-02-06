<?php

namespace Tests\Feature\Livewire\Products;

use App\Http\Livewire\Products\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $this->withoutExceptionHandling();

        $this->loginAsAdmin();

        Livewire::test(Index::class)
                ->assertStatus(200);
    }
}
