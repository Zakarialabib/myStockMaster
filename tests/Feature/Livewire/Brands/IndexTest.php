<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Brands;

use App\Http\Livewire\Brands\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_brands_component_can_render()
    {
        $this->withoutExceptionHandling();

        $this->loginAsAdmin();

        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('livewire.brands.index');
    }
}
