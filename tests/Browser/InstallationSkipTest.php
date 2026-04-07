<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class InstallationSkipTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        // Clear any cached settings
        cache()->forget('settings');
    }

    /** @test */
    public function it_shows_skip_message_when_installation_is_configured_to_skip()
    {
        Config::set('installation.skip', true);

        $this->browse(function (Browser $browser) {
            $browser->visit('/install')
                ->assertSee('Installation Skipped')
                ->assertSee('The installation process has been bypassed')
                ->assertDontSee('Step 1 of')
                ->assertDontSee('Company Information');
        });
    }

    /** @test */
    public function it_shows_already_installed_message_when_installation_is_completed()
    {
        Config::set('installation.skip', false);
        Config::set('installation.force', false);

        // Create settings with completed installation
        Setting::create([
            'company_name' => 'Test Company',
            'company_email' => 'test@company.com',
            'company_phone' => '123-456-7890',
            'company_address' => '123 Test St',
            'default_currency_id' => 1,
            'default_currency_position' => 'before',
            'default_date_format' => 'Y-m-d',
            'default_language' => 'en',
            'installation_completed' => true,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/install')
                ->assertSee('Already Installed')
                ->assertSee('has already been installed and configured')
                ->assertDontSee('Step 1 of')
                ->assertDontSee('Company Information');
        });
    }

    /** @test */
    public function it_shows_installation_process_when_needed()
    {
        Config::set('installation.skip', false);
        Config::set('installation.force', false);

        // Create settings with incomplete installation
        Setting::create([
            'company_name' => 'Test Company',
            'company_email' => 'test@company.com',
            'company_phone' => '123-456-7890',
            'company_address' => '123 Test St',
            'default_currency_id' => 1,
            'default_currency_position' => 'before',
            'default_date_format' => 'Y-m-d',
            'default_language' => 'en',
            'installation_completed' => false,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/install')
                ->assertSee('RestoPos Installation')
                ->assertSee('Step 1 of')
                ->assertSee('Company Information')
                ->assertDontSee('Installation Skipped')
                ->assertDontSee('Already Installed');
        });
    }

    /** @test */
    public function it_can_navigate_through_installation_steps_without_page_refresh()
    {
        Config::set('installation.skip', false);
        Config::set('installation.force', false);

        // Create settings with incomplete installation
        Setting::create([
            'company_name' => 'Test Company',
            'company_email' => 'test@company.com',
            'company_phone' => '123-456-7890',
            'company_address' => '123 Test St',
            'default_currency_id' => 1,
            'default_currency_position' => 'before',
            'default_date_format' => 'Y-m-d',
            'default_language' => 'en',
            'installation_completed' => false,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/install')
                ->assertSee('Step 1 of')
                ->assertSee('Company Information')
                    // Fill required fields
                ->type('company_name', 'Test Company Updated')
                ->type('company_email', 'updated@test.com')
                ->type('company_phone', '555-123-4567')
                ->type('company_address', '456 Updated St')
                    // Click next step button
                ->click('button[wire\\:click="nextStep"]')
                ->waitForText('Step 2 of')
                ->assertSee('Demo Data')
                ->assertDontSee('Step 1 of')
                ->assertDontSee('Company Information')
                    // Verify we can go back
                ->click('button[wire\\:click="previousStep"]')
                ->waitForText('Step 1 of')
                ->assertSee('Company Information')
                ->assertDontSee('Step 2 of');
        });
    }

    /** @test */
    public function it_handles_validation_errors_without_page_refresh()
    {
        Config::set('installation.skip', false);
        Config::set('installation.force', false);

        // Create settings with incomplete installation
        Setting::create([
            'company_name' => 'Test Company',
            'company_email' => 'test@company.com',
            'company_phone' => '123-456-7890',
            'company_address' => '123 Test St',
            'default_currency_id' => 1,
            'default_currency_position' => 'before',
            'default_date_format' => 'Y-m-d',
            'default_language' => 'en',
            'installation_completed' => false,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/install')
                ->assertSee('Step 1 of')
                ->assertSee('Company Information')
                    // Clear required fields to trigger validation
                ->clear('company_name')
                ->clear('company_email')
                    // Try to proceed to next step
                ->click('button[wire\\:click="nextStep"]')
                ->waitForText('Please fix the following errors')
                ->assertSee('The company name field is required')
                ->assertSee('The company email field is required')
                    // Verify we're still on step 1
                ->assertSee('Step 1 of')
                ->assertSee('Company Information');
        });
    }

    /** @test */
    public function it_forces_installation_when_configured_even_if_completed()
    {
        Config::set('installation.skip', false);
        Config::set('installation.force', true);

        // Create settings with completed installation
        Setting::create([
            'company_name' => 'Test Company',
            'company_email' => 'test@company.com',
            'company_phone' => '123-456-7890',
            'company_address' => '123 Test St',
            'default_currency_id' => 1,
            'default_currency_position' => 'before',
            'default_date_format' => 'Y-m-d',
            'default_language' => 'en',
            'installation_completed' => true,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/install')
                ->assertSee('RestoPos Installation')
                ->assertSee('Step 1 of')
                ->assertSee('Company Information')
                ->assertDontSee('Already Installed')
                ->assertDontSee('Installation Skipped');
        });
    }
}
