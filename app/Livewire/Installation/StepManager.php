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
    use WithAlert;
    use WithFileUploads;

    public int $currentStep = 1;

    public ?string $persona = null; // 'retail' or 'technician'

    public bool $isDesktopMode = false;

    public bool $isInstalled = false;

    // ── Preflight (web only) ──
    public array $preflightResults = [];

    public array $requirementErrors = [];

    public bool $preflightPassed = false;

    // ── Database (web only) ──
    public array $database = [
        'connection' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => '',
        'username' => 'root',
        'password' => '',
    ];

    public bool $connectionTested = false;

    public bool $connectionSuccess = false;

    public string $connectionMessage = '';

    public bool $migrationsDone = false;

    // ── Company details ──
    public mixed $company_name;

    public mixed $company_email;

    public mixed $company_phone;

    public mixed $company_address;

    public mixed $company_tax;

    // ── Demo selection ──
    public $selected_business_line = '';

    public $install_demo_data = true;

    public bool $retailQuickStart = false;

    // ── Admin user details ──
    public mixed $admin_name;

    public mixed $admin_email;

    public mixed $admin_password;

    public mixed $admin_password_confirmation;

    public function mount(): void
    {
        $this->isDesktopMode = \App\Services\EnvironmentService::isDesktop() && ! app()->runningUnitTests();
        $this->isInstalled = $this->shouldSkipInstallation();

        if ($this->isDesktopMode) {
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
            }
        } catch (Exception) {
            // Ignore if DB not ready
        }

        // Setup initial config if not desktop
        if (! $this->isDesktopMode) {
            $this->database['connection'] = env('DB_CONNECTION', 'mysql');
            $this->database['host'] = env('DB_HOST', '127.0.0.1');
            $this->database['port'] = env('DB_PORT', '3306');
            $this->database['database'] = env('DB_DATABASE', '');
            $this->database['username'] = env('DB_USERNAME', 'root');
            
            $this->admin_email = 'admin@example.com';
            
            $this->runPreflightChecks();
        }
    }

    public function shouldSkipInstallation(): bool
    {
        if (config('installation.skip', false)) {
            return true;
        }

        try {
            if (Schema::hasTable('settings')) {
                return settings('installation_completed', false) && ! config('installation.force', false);
            }
        } catch (Exception) {
            return false;
        }

        return false;
    }

    public function getStepsProperty(): array
    {
        if (! $this->persona) {
            return ['persona'];
        }

        if ($this->persona === 'retail' && $this->isDesktopMode) {
            return ['persona', 'company', 'admin', 'demo', 'finish'];
        }

        // Default Technician or Web Retail flow
        return ['persona', 'requirements', 'database', 'company', 'admin', 'demo', 'finish'];
    }

    public function getStepTitleProperty(): string
    {
        $step = $this->steps[$this->currentStep - 1] ?? '';

        return match ($step) {
            'persona' => __('Choose Your Persona'),
            'requirements' => __('System Requirements'),
            'database' => __('Database Configuration'),
            'company' => __('Company Details'),
            'admin' => __('Admin Account'),
            'demo' => __('Demo Data'),
            'finish' => __('Complete Installation'),
            default => __('Installation'),
        };
    }

    public function selectPersona(string $persona): void
    {
        $this->persona = $persona;
        $this->nextStep();
    }

    public function nextStep(): void
    {
        $this->validateCurrentStep();

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

    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= count($this->steps)) {
            try {
                // Only validate if going forward
                if ($step > $this->currentStep) {
                    $this->validateCurrentStep();
                }

                $this->currentStep = $step;
            } catch (\Illuminate\Validation\ValidationException) {
                $this->alert('error', __('Please complete the current step before proceeding.'));
            }
        }
    }

    private function validateCurrentStep(): void
    {
        $stepName = $this->steps[$this->currentStep - 1] ?? '';

        match ($stepName) {
            'persona' => $this->validatePersona(),
            'requirements' => $this->validatePreflight(),
            'database' => $this->validateDatabase(),
            'company' => $this->validateCompanyDetails(),
            'admin' => $this->validateAdminDetails(),
            'demo' => $this->validateDemoSelection(),
            default => null,
        };
    }

    private function validatePersona(): void
    {
        $this->validate([
            'persona' => 'required|in:retail,technician',
        ]);
    }

    private function validatePreflight(): void
    {
        $this->runPreflightChecks();

        if (! $this->preflightPassed) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'preflight' => __('Some system requirements are not met. Please fix them before continuing.'),
            ]);
        }
    }

    public function runPreflightChecks(): void
    {
        $this->preflightResults = [];
        $this->requirementErrors = [];

        // PHP version
        $phpOk = PHP_VERSION_ID >= 80200;
        $this->preflightResults['php_version'] = [
            'label' => 'PHP Version (≥ 8.2)',
            'value' => PHP_VERSION,
            'passed' => $phpOk,
        ];

        if (! $phpOk) {
            $this->requirementErrors[] = 'PHP 8.2 or higher is required. Current: ' . PHP_VERSION;
        }

        // Extensions
        $requiredExtensions = ['BCMath', 'Ctype', 'DOM', 'Fileinfo', 'JSON', 'Mbstring', 'OpenSSL', 'PCRE', 'PDO', 'Tokenizer', 'XML', 'sqlite3'];

        foreach ($requiredExtensions as $requiredExtension) {
            $loaded = extension_loaded(strtolower($requiredExtension));
            $this->preflightResults['ext_' . strtolower($requiredExtension)] = [
                'label' => $requiredExtension . ' Extension',
                'passed' => $loaded,
            ];

            if (! $loaded) {
                $this->requirementErrors[] = sprintf("PHP extension '%s' is required but not loaded.", $requiredExtension);
            }
        }

        // Directory permissions
        $writableDirs = [
            storage_path() => 'storage',
            storage_path('app') => 'storage/app',
            storage_path('framework') => 'storage/framework',
            storage_path('logs') => 'storage/logs',
            base_path('bootstrap/cache') => 'bootstrap/cache',
        ];

        foreach ($writableDirs as $path => $label) {
            $writable = is_writable($path);
            $this->preflightResults['dir_' . str_replace('/', '_', $label)] = [
                'label' => 'Directory: ' . $label,
                'passed' => $writable,
            ];

            if (! $writable) {
                $this->requirementErrors[] = sprintf("Directory '%s' is not writable.", $label);
            }
        }

        // .env file
        $envExists = file_exists(base_path('.env'));
        $this->preflightResults['env_file'] = [
            'label' => '.env file exists',
            'passed' => $envExists,
            'hint' => $envExists ? null : 'Run: cp .env.example .env && php artisan key:generate',
        ];

        if (! $envExists) {
            $this->requirementErrors[] = '.env file not found. Copy .env.example to .env and run php artisan key:generate.';
        }

        $this->preflightPassed = $this->requirementErrors === [];
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

        $this->connectionTested = true;

        try {
            $config = config('database.connections.' . $this->database['connection'], []);
            $config['driver'] = $this->database['connection'];
            $config['host'] = $this->database['host'];
            $config['port'] = $this->database['port'];
            $config['database'] = $this->database['database'];
            $config['username'] = $this->database['username'];
            $config['password'] = $this->database['password'];

            if ($this->database['connection'] === 'sqlite') {
                $dbPath = $this->database['database'] ?: database_path('database.sqlite');

                if (! file_exists($dbPath)) {
                    touch($dbPath);
                }

                $config['database'] = $dbPath;
            }

            Config::set('database.connections._install_test', $config);
            DB::connection('_install_test')->getPdo();
            DB::disconnect('_install_test');

            $this->connectionSuccess = true;
            $this->connectionMessage = __('Database connection successful!');

            // Clean flash session so an old error doesn't block validation
            session()->forget('connection_error');
            $this->alert('success', $this->connectionMessage);
        } catch (Exception $exception) {
            $this->connectionSuccess = false;
            $this->connectionMessage = __('Connection failed: ') . $exception->getMessage();
            session()->flash('connection_error', $this->connectionMessage);
            $this->alert('error', $this->connectionMessage);
            Log::warning('Installation DB test failed', ['error' => $exception->getMessage()]);
        }
    }

    private function validateDatabase(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        // Attempt connection test if not passed
        if (! $this->connectionSuccess) {
            $this->testConnection();
        }

        if (session()->has('connection_error') || ! $this->connectionSuccess) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'database.database' => 'Database connection failed. Please check your settings and test the connection.',
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

        if (! file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $content = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            if (preg_match(sprintf('/^%s=.*/m', $key), $content)) {
                $content = preg_replace(sprintf('/^%s=.*/m', $key), sprintf('%s=%s', $key, $value), $content);
            } else {
                $content .= sprintf('%s%s=%s', PHP_EOL, $key, $value);
            }
        }

        file_put_contents($envPath, $content);
        Artisan::call('config:clear');
    }

    private function validateCompanyDetails(): void
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email',
            'company_phone' => 'nullable|string',
            'company_address' => 'nullable|string',
        ]);
    }

    private function validateAdminDetails(): void
    {
        $this->validate([
            'admin_email' => 'required|email' . (Schema::hasTable('users') ? '|unique:users,email' : ''),
            'admin_password' => 'required|min:8|confirmed',
        ]);
    }

    private function validateDemoSelection(): void
    {
        if ($this->install_demo_data) {
            $this->validate([
                'selected_business_line' => 'required|string|in:electronics,sports,fashion,restaurant,grocery,automotive,books,pharmacy,jewelry,furniture',
            ]);
        }
    }

    public function completeInstallation()
    {
        try {
            if ($this->persona === 'retail' && $this->isDesktopMode) {
                $this->setupDesktopDatabase();
            }

            // Run migrations
            $migrateExitCode = Artisan::call('migrate', ['--force' => true]);

            if ($migrateExitCode !== 0) {
                $output = Artisan::output();

                throw new Exception('Database migration failed. Details in logs.');
                // Note: The specific output can be too large or contain sensitive info, better to log it and show a generic message or just the last error line
            }

            // Ensure settings table exists and has a record
            if (Setting::query()->count() === 0) {
                $seeders = [
                    \Database\Seeders\RolesAndPermissionsSeeder::class,
                    \Database\Seeders\CurrencySeeder::class,
                    \Database\Seeders\SettingsSeeder::class,
                    \Database\Seeders\LanguagesSeeder::class,
                ];

                foreach ($seeders as $seeder) {
                    $exitCode = Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);

                    throw_if($exitCode !== 0, Exception::class, sprintf('Core data insertion failed for %s.', $seeder));
                }
            }

            // Create admin user
            $user = User::query()->updateOrCreate(['email' => $this->admin_email], [
                'name' => $this->admin_name ?? explode('@', $this->admin_email ?? '')[0],
                'password' => Hash::make($this->admin_password),
                'is_admin' => true,
            ]);

            // Assign roles if Spatie is used
            if (method_exists($user, 'assignRole')) {
                // Ensure role seeder has run
                $user->assignRole('admin');
            }

            // Update settings
            $this->saveCompanyDetails();

            // Get the settings record and update it
            $settings = Setting::query()->first();

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

            return to_route('dashboard');
        } catch (Exception $exception) {
            $this->alert('error', __('Installation failed: ') . $exception->getMessage());
            Log::error('Installation completion failed', ['error' => $exception->getMessage()]);
        }
    }

    private function saveCompanyDetails(): void
    {
        $settings = Setting::query()->first();

        if ($settings) {
            $settings->update([
                'company_name' => $this->company_name,
                'company_email' => $this->company_email,
                'company_phone' => $this->company_phone,
                'company_address' => $this->company_address,
                'company_tax' => $this->company_tax,
                'install_demo_data' => $this->install_demo_data,
                'selected_business_line' => $this->selected_business_line,
            ]);
        }
    }

    private function setupDesktopDatabase(): void
    {
        $dbPath = storage_path('database/desktop.sqlite');

        if (! file_exists(dirname($dbPath))) {
            mkdir(dirname($dbPath), 0755, true);
        }

        if (! file_exists($dbPath)) {
            touch($dbPath);
        }

        $this->updateEnv([
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => $dbPath,
            'DESKTOP_DB_URL' => '',
            'DESKTOP_DB_DATABASE' => $dbPath,
        ]);

        Config::set('database.default', 'sqlite_desktop');
        Config::set('database.connections.sqlite_desktop.database', $dbPath);
        DB::purge('sqlite_desktop');
        DB::reconnect('sqlite_desktop');
    }

    private function installDemoData(): void
    {
        try {
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\ComprehensiveDataSeeder::class,
                '--force' => true,
            ]);

            Log::info('Demo data installed successfully', ['business_line' => $this->selected_business_line]);
        } catch (Exception $exception) {
            Log::error('Failed to install demo data', ['error' => $exception->getMessage()]);
            $this->alert('warning', __('Demo data installation failed: ') . $exception->getMessage());
        }
    }
}
