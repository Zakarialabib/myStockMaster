<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Settings;

use App\Livewire\Settings\MaintenanceMode;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    public function test_component_instantiates(): void
    {
        $component = new MaintenanceMode;
        $this->assertInstanceOf(MaintenanceMode::class, $component);
    }
}
