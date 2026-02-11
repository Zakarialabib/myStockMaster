<?php

declare(strict_types=1);

namespace App\Livewire\Installation;

use App\Models\Admin;
use App\Models\Setting;
use App\Models\User;
use App\Traits\WithAlert;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.guest')]
class StepManager extends Component
{
    use WithFileUploads;
    use WithAlert;

    public $currentStep = 1;

    public $totalSteps = 5;

    // Company details
    public $company_name;

    public $company_email;

    public $company_phone;

    public $company_address;

    public $company_tax;

    // Demo selection
    public $selected_business_line = '';

    public $install_demo_data = true;

    // Site settings
    public $site_logo;

    public $multi_language = true;

    public $currency = 'MAD';

    public $timezone = 'UTC';

    public $items_per_page = 20;

    // Admin user details
    public $admin_name;

    public $admin_email;

    public $admin_password;

    public $admin_password_confirmation;

    public function mount(): void
    {
        // Check if installation should be skipped or is already completed
        if ($this->shouldSkipInstallation()) {
            return;
        }

        // Load current step from settings if available
        $this->currentStep = 1;

        // Pre-fill values if they exist in settings
        $this->company_name = settings('company_name', '');
        $this->company_email = settings('company_email', '');
        $this->company_phone = settings('company_phone', '');
        $this->company_address = settings('company_address', '');
        $this->company_tax = settings('company_tax', '');
        $this->selected_business_line = settings('selected_business_line', '');
        $this->install_demo_data = settings('install_demo_data', true);
        $this->currency = settings('currency', 'MAD');
        $this->timezone = settings('timezone', 'UTC');
        $this->items_per_page = settings('items_per_page', 20);
    }

    /** Check if installation should be skipped */
    private function shouldSkipInstallation(): bool
    {
        // Skip if explicitly configured to skip
        if (config('installation.skip', false)) {
            return true;
        }

        // Skip if installation is completed and not forced
        return (bool) (settings('installation_completed', false) && ! config('installation.force', false));
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            $this->validateCompanyDetails();
        } elseif ($this->currentStep === 2) {
            $this->validateDemoSelection();
        } elseif ($this->currentStep === 3) {
            $this->validateSiteSettings();
        } elseif ($this->currentStep === 4) {
            $this->validateAdminDetails();
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->saveCurrentStep();
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->saveCurrentStep();
        }
    }

    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            // Validate current step before allowing navigation
            try {
                if ($this->currentStep === 1 && $step > 1) {
                    $this->validateCompanyDetails();
                }

                if ($this->currentStep === 2 && $step > 2) {
                    $this->validateDemoSelection();
                }

                if ($this->currentStep === 3 && $step > 3) {
                    $this->validateSiteSettings();
                }

                if ($this->currentStep === 4 && $step > 4) {
                    $this->validateAdminDetails();
                }

                $this->currentStep = $step;
                $this->saveCurrentStep();
            } catch (\Illuminate\Validation\ValidationException $e) {
                // If validation fails, show error but don't navigate
                $this->alert('error', __('Please complete the current step before proceeding.'));
            }
        }
    }

    public function saveCurrentStep(): void
    {
        switch ($this->currentStep) {
            case 1:
                $this->validateCompanyDetails();

                break;
            case 2:
                // Admin user validation handled in nextStep
                break;
            case 3:
                // Demo data selection - no validation needed
                break;
            case 4:
                // Site settings validation handled in nextStep
                break;
            default:
                break;
        }
    }

    public function save(): void
    {
        $this->saveCurrentStep();
    }

    public function completeInstallation(): void
    {
        try {
            // Get the settings record and update it
            $settings = Setting::firstOrFail();
            $settings->installation_completed = true;
            $settings->save();

            // Clear settings cache
            cache()->forget('settings');

            // Install demo data if selected
            if ($this->install_demo_data && $this->selected_business_line) {
                $this->installDemoData();
            }

            $this->alert('success', __('Installation completed successfully!'));
        } catch (Exception $e) {
            $this->alert('error', __('Installation failed: ').$e->getMessage());
        }

        // Always set to step 5 regardless of success or failure
        $this->currentStep = 5;
    }

    private function installDemoData(): void
    {
        try {
            // Set the selected business line in settings for the seeder
            $settings = Setting::firstOrFail();
            $settings->update(['selected_business_line' => $this->selected_business_line]);
            cache()->forget('settings');

            // Run the comprehensive product seeder
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ComprehensiveProductSeeder',
            ]);

            Log::info('Demo data installed successfully', [
                'business_line' => $this->selected_business_line,
            ]);

            $this->alert('info', __('Demo data installed for: ').$this->selected_business_line);
        } catch (Exception $e) {
            Log::error('Failed to install demo data', [
                'error'         => $e->getMessage(),
                'business_line' => $this->selected_business_line,
            ]);

            $this->alert('warning', __('Demo data installation failed: ').$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.installation.step-manager');
    }

    private function validateCompanyDetails(): void
    {
        $this->validate([
            'company_name'    => 'required|string|max:255',
            'company_email'   => 'required|email',
            'company_phone'   => 'required|string',
            'company_address' => 'required|string',
        ]);

        // Save company details to settings
        $this->updateSetting('company_name', $this->company_name);
        $this->updateSetting('company_email', $this->company_email);
        $this->updateSetting('company_phone', $this->company_phone);
        $this->updateSetting('company_address', $this->company_address);
        $this->updateSetting('company_tax', $this->company_tax);
    }

    private function validateDemoSelection(): void
    {
        if ($this->install_demo_data) {
            $this->validate([
                'selected_business_line' => 'required|string|in:electronics,sports,fashion,restaurant,grocery,automotive,books,pharmacy,jewelry,furniture',
            ]);
        }

        // Save demo selection settings
        $this->updateSetting('selected_business_line', $this->selected_business_line);
        $this->updateSetting('install_demo_data', $this->install_demo_data);
    }

    private function validateSiteSettings(): void
    {
        $this->validate([
            'site_logo'      => 'nullable|image|max:1024',
            'currency'       => 'required|string|max:3',
            'timezone'       => 'required|string',
            'items_per_page' => 'required|integer|min:5|max:100',
        ]);

        // Handle logo upload if provided
        if ($this->site_logo) {
            $logoPath = $this->site_logo->store('logos', 'public');
            $this->updateSetting('site_logo', $logoPath);
        }

        // Save site settings
        $this->updateSetting('multi_language', $this->multi_language);
        $this->updateSetting('currency', $this->currency);
        $this->updateSetting('timezone', $this->timezone);
        $this->updateSetting('items_per_page', $this->items_per_page);
    }

    private function validateAdminDetails(): void
    {
        $this->validate([
            // 'admin_name' => 'required|string|max:255',
            'admin_email'    => 'required|email|unique:users,email',
            'admin_password' => 'required|min:8|confirmed',
        ]);

        // Create admin user
        User::create([
            'name'     => $this->admin_name,
            'email'    => $this->admin_email,
            'password' => Hash::make($this->admin_password),
            'is_admin' => true,
        ]);
    }

    private function updateSetting(string $key, $value): void
    {
        $settings = Setting::firstOrFail();
        $settings->update([$key => $value]);
        cache()->forget('settings');
    }
}
