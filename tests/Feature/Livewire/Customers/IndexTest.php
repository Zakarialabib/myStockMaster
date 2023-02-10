<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Customer;

use App\Http\Livewire\Customers\Index;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function index_customers_component_can_render()
    {
        $this->withoutExceptionHandling();
        $this->loginAsAdmin();

        Livewire::test(Index::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.customers.index');
    }
}
