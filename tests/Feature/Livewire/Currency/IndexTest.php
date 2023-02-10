<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Currency;

use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function index_currency_component_can_render()
    {
        $this->withoutExceptionHandling();
        $this->loginAsAdmin();

        Livewire::test(Index::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.currency.index');
    }
}
