<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Settings;

use App\Livewire\Settings\Languages;
use Tests\TestCase;

class LanguagesTest extends TestCase
{
    public function test_component_instantiates(): void
    {
        $component = new Languages;
        $this->assertInstanceOf(Languages::class, $component);
    }
}
