<?php

declare(strict_types=1);

use App\Livewire\Installation\StepManager;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    cache()->forget('settings');
});

it('initializes with persona selection', function () {
    Config::set('installation.skip', false);

    Livewire::test(StepManager::class)
        ->assertSet('currentStep', 1)
        ->assertSet('persona', null)
        ->assertSee('Who are you?');
});

it('can select retail persona (web flow)', function () {
    Livewire::test(StepManager::class)
        ->call('selectPersona', 'retail')
        ->assertSet('persona', 'retail')
        ->assertSet('currentStep', 2)
        ->assertSee('System Requirements'); // Non-desktop Retail still sees requirements
});

it('can select technician persona', function () {
    Livewire::test(StepManager::class)
        ->call('selectPersona', 'technician')
        ->assertSet('persona', 'technician')
        ->assertSet('currentStep', 2)
        ->assertSee('System Requirements');
});

it('validates company details in retail flow', function () {
    // Retail flow (web): 1: Persona -> 2: Requirements -> 3: Database -> 4: Company
    Livewire::test(StepManager::class)
        ->set('persona', 'retail')
        ->set('currentStep', 4)
        ->set('company_name', '')
        ->call('nextStep')
        ->assertHasErrors(['company_name']);
});

it('can navigate back to persona selection', function () {
    Livewire::test(StepManager::class)
        ->call('selectPersona', 'retail')
        ->assertSet('currentStep', 2)
        ->call('previousStep')
        ->assertSet('currentStep', 1)
        ->assertSee('Who are you?');
});

it('shows already installed view', function () {
    Livewire::test(StepManager::class)
        ->set('isInstalled', true)
        ->assertSee('Already Installed');
});
