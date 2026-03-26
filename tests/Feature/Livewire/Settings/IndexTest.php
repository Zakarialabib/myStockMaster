<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Settings;

use App\Livewire\Settings\Index;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_component_instantiates(): void
    {
        $component = new Index;
        $this->assertInstanceOf(Index::class, $component);
    }
}
