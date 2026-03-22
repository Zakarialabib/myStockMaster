<?php

declare(strict_types=1);

namespace App\Livewire\Installation;

use App\Models\Setting;
use App\Models\User;
use App\Traits\WithAlert;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.guest')]
class StepManager extends Component
{
    use WithFileUploads;
    use WithAlert;

    public $currentStep = 1;
    public $persona = null; // 'retail' or 'technician'
    public $isDesktop = false;
    public $isInstalled = false;

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
    public $admin_name = 'Admin';
    public $admin_email;
    public $admin_password;
    public $admin_password_confirmation;

    // Database details
    public $database = [
        'connection' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => '',
        'username' => '',
        'password' => '',
    ];

    public $requirementErrors = [];

    public function mount(): void
    {
        $this->isDesktop = $this->detectEnvironment();
        $this->isInstalled = $this->shouldSkipInstallation();

        if ($this->isDesktop) {
            $this->persona = 'retail';
        }

        // Pre-fill values
        try {
            if (Schema::hasTable('settings')) {
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
        } catch (Exception $e) {
            // Ignore if DB not ready
        }
    }

    private function detectEnvironment(): bool
    {
        return class_exists(\Native\Desktop\Facades\Window::class) && !app()->runningUnitTests();
    }

    public function shouldSkipInstallation(): bool
    {
        if (config('installation.skip', false)) {
            return true;
        }

        try {
            if (Schema::hasTable('settings')) {
                return (bool) (settings('installation_completed', false) && ! config('installation.force', false));
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    public function getStepsProperty()
    {
        if (!$this->persona) {
            return ['persona'];
        }

        if ($this->persona === 'retail' && $this->isDesktop) {
            return ['persona', 'company', 'admin', 'demo', 'finish'];
        }

        // Default Technician or Web Retail flow
        return ['persona', 'requirements', 'database', 'company', 'admin', 'demo', 'finish'];
    }

    public function getStepTitleProperty()
    {
        $step = $this->steps[$this->currentStep - 1] ?? '';
        return match($step) {
            'persona' => 'Choose Your Persona',
            'requirements' => 'System Requirements',
            'database' => 'Database Configuration',
            'company' => 'Company Details',
            'admin' => 'Admin Account',
            'demo' => 'Demo Data',
            'finish' => 'Complete Installation',
            default => 'Installation',
        };
    }

    public function nextStep(): void
    {
        $stepName = $this->steps[$this->currentStep - 1];

        if ($stepName === 'persona') {
            if (!$this->persona) {
                $this->addError('persona', 'Please select a persona to continue.');
                return;
            }
        } elseif ($stepName === 'requirements') {
            $this->checkRequirements();
            if (count($this->requirementErrors) > 0) {
                return;
            }
        } elseif ($stepName === 'database') {
            $this->validateDatabase();
        } elseif ($stepName === 'company') {
            $this->validateCompanyDetails();
        } elseif ($stepName === 'admin') {
            $this->validateAdminDetails();
        } elseif ($stepName === 'demo') {
            $this->validateDemoSelection();
        }

        if ($this->currentStep < count($this->steps)) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function selectPersona($persona): void
    {
        $this->persona = $persona;
        $this->nextStep();
    }

    public function checkRequirements(): void
    {
        $this->requirementErrors = [];

        if (version_compare(PHP_VERSION, '8.2.0', '<')) {
            $this->requirementErrors[] = 'PHP 8.2 or higher is required.';
        }

        $requiredExtensions = ['BCMath', 'Ctype', 'DOM', 'Fileinfo', 'JSON', 'Mbstring', 'OpenSSL', 'PCRE', 'PDO', 'Tokenizer', 'XML', 'sqlite3'];
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded(strtolower($ext))) {
                $this->requirementErrors[] = "Extension $ext is missing.";
            }
        }

        $writablePaths = [
            storage_path(),
            base_path('bootstrap/cache'),
            base_path('.env'),
        ];

        foreach ($writablePaths as $path) {
            if (file_exists($path) && !is_writable($path)) {
                $this->requirementErrors[] = "Path $path is not writable.";
            }
        }
    }

    public function testConnection(): void
    {
        $this->validate([
            'database.connection' => 'required',
            'database.database' => 'required',
        ]);

        if ($this->database['connection'] !== 'sqlite') {
            $this->validate([
                'database.host' => 'required',
                'database.username' => 'required',
            ]);
        }

        try {
            $config = config('database.connections.' . $this->database['connection']);
            $config['host'] = $this->database['host'];
            $config['port'] = $this->database['port'];
            $config['database'] = $this->database['database'];
            $config['username'] = $this->database['username'];
            $config['password'] = $this->database['password'];

            Config::set('database.connections.temp', $config);
            DB::connection('temp')->getPdo();

            session()->flash('connection_success', 'Connection successful!');
        } catch (Exception $e) {
            session()->flash('connection_error', 'Connection failed: ' . $e->getMessage());
        }
    }

    private function validateDatabase(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        // For Technician, we want them to have a working DB
        $this->testConnection();
        if (session()->has('connection_error')) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'database.database' => 'Database connection failed. Please check your settings.',
            ]);
        }

        // Update .env file
        $this->updateEnv([
            'DB_CONNECTION' => $this->database['connection'],
            'DB_HOST' => $this->database['host'],
            'DB_PORT' => $this->database['port'],
            'DB_DATABASE' => $this->database['database'],
            'DB_USERNAME' => $this->database['username'],
            'DB_PASSWORD' => $this->database['password'],
        ]);
    }

    private function updateEnv(array $data): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $content = file_get_contents($envPath);
        foreach ($data as $key => $value) {
            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }
        file_put_contents($envPath, $content);
    }

    public function completeInstallation()
    {
        try {
            if ($this->persona === 'retail' && $this->isDesktop) {
                $this->setupDesktopDatabase();
            }

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // Ensure settings table exists and has a record
            if (Setting::count() === 0) {
                Artisan::call('db:seed', ['--class' => 'SettingSeeder', '--force' => true]);
            }

            // Create admin user
            $user = User::updateOrCreate(
                ['email' => $this->admin_email],
                [
                    'name' => $this->admin_name,
                    'password' => Hash::make($this->admin_password),
                    'is_admin' => true,
                ]
            );

            // Assign roles if Spatie is used
            if (method_exists($user, 'assignRole')) {
                Artisan::call('db:seed', ['--class' => 'RoleSeeder', '--force' => true]);
                $user->assignRole('admin');
            }

            // Update settings
            $this->saveCompanyDetails();

            // Get the settings record and update it
            $settings = Setting::first();
            if ($settings) {
                $settings->installation_completed = true;
                $settings->save();
            }

            // Clear settings cache
            cache()->forget('settings');

            // Install demo data if selected
            if ($this->install_demo_data && $this->selected_business_line) {
                $this->installDemoData();
            }

            $this->alert('success', __('Installation completed successfully!'));

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            $this->alert('error', __('Installation failed: ').$e->getMessage());
        }
    }

    private function setupDesktopDatabase(): void
    {
        $dbPath = database_path('database.sqlite');
        if (!file_exists($dbPath)) {
            touch($dbPath);
        }

        $this->updateEnv([
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => $dbPath,
        ]);

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', $dbPath);
    }

    private function installDemoData(): void
    {
        try {
            $settings = Setting::first();
            if ($settings) {
                $settings->update(['selected_business_line' => $this->selected_business_line]);
                cache()->forget('settings');
            }

            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ComprehensiveProductSeeder',
                '--force' => true
            ]);

            Log::info('Demo data installed successfully', ['business_line' => $this->selected_business_line]);
        } catch (Exception $e) {
            Log::error('Failed to install demo data', ['error' => $e->getMessage()]);
            $this->alert('warning', __('Demo data installation failed: ').$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.installation.step-manager', [
            'steps' => $this->steps,
            'stepTitle' => $this->stepTitle,
        ]);
    }

    private function validateCompanyDetails(): void
    {
        $this->validate([
            'company_name'    => 'required|string|max:255',
            'company_email'   => 'required|email',
            'company_phone'   => 'required|string',
            'company_address' => 'required|string',
        ]);
    }

    private function saveCompanyDetails(): void
    {
        $settings = Setting::first();
        if ($settings) {
            $settings->update([
                'company_name' => $this->company_name,
                'company_email' => $this->company_email,
                'company_phone' => $this->company_phone,
                'company_address' => $this->company_address,
                'company_tax' => $this->company_tax,
                'default_currency_id' => 1, // Defaulting for now
                'default_date_format' => 'd-m-Y',
            ]);
            cache()->forget('settings');
        }
    }

    private function validateDemoSelection(): void
    {
        if ($this->install_demo_data) {
            $this->validate([
                'selected_business_line' => 'required|string',
            ]);
        }
    }

    private function validateAdminDetails(): void
    {
        $this->validate([
            'admin_email'    => 'required|email',
            'admin_password' => 'required|min:8|confirmed',
        ]);
    }
}
