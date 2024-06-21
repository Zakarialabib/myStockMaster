<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Products;

use App\Livewire\Products\Index;
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
            ->assertStatus(200)
            ->assertViewIs('livewire.products.index');
    }
}
