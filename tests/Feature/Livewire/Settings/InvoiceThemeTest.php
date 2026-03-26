<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Settings;

use App\Livewire\Settings\InvoiceTheme;
use Tests\TestCase;

class InvoiceThemeTest extends TestCase
{
    public function test_component_instantiates(): void
    {
        $component = new InvoiceTheme;
        $this->assertInstanceOf(InvoiceTheme::class, $component);
    }
}
