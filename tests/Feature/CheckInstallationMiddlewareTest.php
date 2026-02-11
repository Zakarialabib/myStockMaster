<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Middleware\CheckInstallation;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CheckInstallationMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Clear cache before each test
        Cache::flush();
    }

    /** @test */
    public function it_allows_installation_routes_to_pass_through()
    {
        $middleware = new CheckInstallation();
        $request = Request::create('/install', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_allows_api_routes_to_pass_through()
    {
        $middleware = new CheckInstallation();
        $request = Request::create('/api/test', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_skips_installation_check_when_skip_installation_is_true()
    {
        Config::set('installation.skip', true);

        $middleware = new CheckInstallation();
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('Dashboard');
        });

        $this->assertEquals('Dashboard', $response->getContent());
    }

    /** @test */
    public function it_forces_installation_when_force_installation_is_true()
    {
        Config::set('installation.force', true);

        $middleware = new CheckInstallation();
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('Dashboard');
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('install', $response->headers->get('Location'));
    }

    /** @test */
    public function it_redirects_to_installation_when_settings_table_does_not_exist()
    {
        // Mock Schema to return false for settings table
        Schema::shouldReceive('hasTable')
            ->with('settings')
            ->andReturn(false);

        $middleware = new CheckInstallation();
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('Dashboard');
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('install', $response->headers->get('Location'));
    }

    /** @test */
    public function it_redirects_to_installation_when_installation_is_not_completed()
    {
        // Create settings table and data
        Setting::create([
            'company_logo'              => 'test-logo.png',
            'company_name'              => 'Test Company',
            'company_email'             => 'test@example.com',
            'company_phone'             => '123-456-7890',
            'company_address'           => '123 Test Street',
            'default_currency_id'       => 1,
            'default_currency_position' => 'before',
            'default_date_format'       => 'Y-m-d',
            'installation_completed'    => false,
        ]);

        $middleware = new CheckInstallation();
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('Dashboard');
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('install', $response->headers->get('Location'));
    }

    /** @test */
    public function it_allows_access_when_installation_is_completed()
    {
        // Create settings table and record
        $setting = Setting::create([
            'company_logo'              => 'test-logo.png',
            'company_name'              => 'Test Company',
            'company_email'             => 'test@example.com',
            'company_phone'             => '123-456-7890',
            'company_address'           => '123 Test Street',
            'default_currency_id'       => 1,
            'default_currency_position' => 'before',
            'default_date_format'       => 'Y-m-d',
        ]);

        // Use the set method to update installation_completed
        $setting->set('installation_completed', true);

        // Debug: Check what's actually stored in the database
        $setting = Setting::first();
        $this->assertNotNull($setting, 'Setting record should exist');
        $this->assertTrue($setting->installation_completed, 'installation_completed should be true in database');

        // Debug: Check what the settings helper returns
        $installationCompleted = settings('installation_completed', false);
        $this->assertTrue($installationCompleted, 'Settings helper should return true for installation_completed');

        $middleware = new CheckInstallation();
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('Dashboard');
        });

        $this->assertEquals('Dashboard', $response->getContent());
    }

    /** @test */
    public function it_prioritizes_skip_installation_over_other_settings()
    {
        Config::set('installation.skip', true);
        Config::set('installation.force', true);

        // Create settings table with incomplete installation
        Setting::create([
            'company_logo'              => 'test-logo.png',
            'company_name'              => 'Test Company',
            'company_email'             => 'test@example.com',
            'company_phone'             => '123-456-7890',
            'company_address'           => '123 Test Street',
            'default_currency_id'       => 1,
            'default_currency_position' => 'before',
            'default_date_format'       => 'Y-m-d',
            'installation_completed'    => false,
        ]);

        $middleware = new CheckInstallation();
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('Dashboard');
        });

        $this->assertEquals('Dashboard', $response->getContent());
    }

    /** @test */
    public function it_forces_installation_even_when_completed_if_force_installation_is_true()
    {
        Config::set('installation.force', true);

        // Create settings table with completed installation
        Setting::create([
            'company_logo'              => 'test-logo.png',
            'company_name'              => 'Test Company',
            'company_email'             => 'test@example.com',
            'company_phone'             => '123-456-7890',
            'company_address'           => '123 Test Street',
            'default_currency_id'       => 1,
            'default_currency_position' => 'before',
            'default_date_format'       => 'Y-m-d',
            'installation_completed'    => true,
        ]);

        $middleware = new CheckInstallation();
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response('Dashboard');
        });

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('install', $response->headers->get('Location'));
    }
}
