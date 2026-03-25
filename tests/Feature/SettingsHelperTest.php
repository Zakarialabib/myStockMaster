<?php

declare(strict_types=1);

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Clear any cached settings
    cache()->forget('settings');
});

describe('Settings Helper Function', function () {
    it('returns all settings when no key is provided', function () {
        // Create settings table and data
        $this->artisan('migrate');
        Setting::create([
            'company_name' => 'Test Company',
            'site_title' => 'Test Site',
            'installation_completed' => true,
        ]);

        $settings = settings();

        expect($settings)->toBeInstanceOf(Setting::class);
        expect($settings->company_name)->toBe('Test Company');
        expect($settings->site_title)->toBe('Test Site');
    });

    it('returns specific setting value when key is provided', function () {
        // Create settings table and data
        $this->artisan('migrate');
        Setting::create([
            'company_name' => 'Test Company',
            'site_title' => 'Test Site',
            'installation_completed' => true,
        ]);

        expect(settings('company_name'))->toBe('Test Company');
        expect(settings('site_title'))->toBe('Test Site');
        expect(settings('installation_completed'))->toBeTrue();
    });

    it('returns default value when key does not exist', function () {
        // Create settings table with minimal data
        $this->artisan('migrate');
        Setting::create([
            'company_name' => 'Test Company',
        ]);

        expect(settings('non_existent_key', 'default_value'))->toBe('default_value');
        expect(settings('missing_setting', null))->toBeNull();
    });

    it('caches settings for performance', function () {
        // Create settings table and data
        $this->artisan('migrate');
        Setting::create([
            'company_name' => 'Test Company',
            'site_title' => 'Test Site',
        ]);

        // First call should cache the settings
        $settings1 = settings();

        // Verify cache exists
        expect(Cache::has('settings'))->toBeTrue();

        // Second call should use cache
        $settings2 = settings();

        expect($settings1->company_name)->toBe($settings2->company_name);
    });

    it('handles empty settings table gracefully', function () {
        // Create empty settings table
        $this->artisan('migrate');

        expect(settings('company_name', 'Default Company'))->toBe('Default Company');
        expect(settings())->toBeNull();
    });
});

describe('Setting Model Static Methods', function () {
    it('can set individual setting values', function () {
        // Create settings table
        $this->artisan('migrate');
        Setting::create([
            'company_name' => 'Old Company',
        ]);

        // Use static set method
        Setting::set('company_name', 'New Company');

        // Verify the change
        $setting = Setting::first();
        expect($setting->company_name)->toBe('New Company');
    });

    it('can get individual setting values', function () {
        // Create settings table and data
        $this->artisan('migrate');
        Setting::create([
            'company_name' => 'Test Company',
            'site_title' => 'Test Site',
        ]);

        expect(Setting::get('company_name'))->toBe('Test Company');
        expect(Setting::get('site_title'))->toBe('Test Site');
        expect(Setting::get('non_existent', 'default'))->toBe('default');
    });

    it('creates new setting record if none exists when using set', function () {
        // Create empty settings table
        $this->artisan('migrate');

        // Set a value when no record exists
        Setting::set('company_name', 'New Company');

        // Verify record was created
        $setting = Setting::first();
        expect($setting)->not->toBeNull();
        expect($setting->company_name)->toBe('New Company');
    });

    it('clears cache when setting values', function () {
        // Create settings table and data
        $this->artisan('migrate');
        Setting::create([
            'company_name' => 'Old Company',
        ]);

        // Cache the settings
        settings();
        expect(Cache::has('settings'))->toBeTrue();

        // Set a new value (should clear cache)
        Setting::set('company_name', 'New Company');

        // Cache should be cleared
        expect(Cache::has('settings'))->toBeFalse();

        // New call should get updated value
        expect(settings('company_name'))->toBe('New Company');
    });
});
