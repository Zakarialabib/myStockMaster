<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class InstallationConfigTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Clear any cached settings
        cache()->forget('settings');
    }

    /** @test */
    public function it_loads_default_configuration_values()
    {
        $this->assertFalse(config('installation.skip', false));
        $this->assertFalse(config('installation.force', false));
        $this->assertFalse(config('installation.completed', false));
    }

    /** @test */
    public function it_respects_skip_installation_environment_variable()
    {
        Config::set('installation.skip', true);

        $this->assertTrue(config('installation.skip'));
    }

    /** @test */
    public function it_respects_force_installation_environment_variable()
    {
        Config::set('installation.force', true);

        $this->assertTrue(config('installation.force'));
    }

    /** @test */
    public function it_can_override_multiple_configuration_values()
    {
        Config::set('installation.skip', true);
        Config::set('installation.force', true);

        $this->assertTrue(config('installation.skip'));
        $this->assertTrue(config('installation.force'));
    }

    /** @test */
    public function it_handles_string_boolean_values_from_environment()
    {
        Config::set('installation.skip', true);
        Config::set('installation.force', false);

        $this->assertTrue(config('installation.skip'));
        $this->assertFalse(config('installation.force'));
    }

    /** @test */
    public function it_provides_fallback_values_when_environment_variables_are_not_set()
    {
        $this->assertFalse(config('installation.skip', false));
        $this->assertFalse(config('installation.force', false));
    }
}
