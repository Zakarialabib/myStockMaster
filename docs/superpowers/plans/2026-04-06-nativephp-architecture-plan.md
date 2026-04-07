# NativePHP Architecture Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Transform the application into a flawless native desktop app by fixing cross-database SQL crashes (MySQL vs. SQLite), moving configurations to a database-backed Settings model, and building a true Native OS Menu that seamlessly triggers Livewire SPA transitions.

**Architecture:** 
- **Database:** Abstract raw SQL (e.g., `DATE_FORMAT`, `YEAR()`) using the existing `db_date_format()` helper and query builder methods to support both MySQL (server) and SQLite (desktop).
- **Settings:** Migrate SMTP and API configs from `.env` to the `settings` table, loaded dynamically via `AppServiceProvider` since `.env` is read-only in compiled NativePHP binaries.
- **Native OS:** Use `Native\Laravel\Facades\Menu` to build a native desktop menu that dispatches global OS events. A global Livewire listener (`NativeEventListener`) catches these and triggers `->navigate()` without breaking the SPA.

**Tech Stack:** NativePHP v2, Laravel 11/12, Livewire v3/v4, SQLite

---

### Task 1: Fix MySQL vs. SQLite Incompatibilities in Action Classes

**Files:**
- Modify: `app/Actions/Analytics/GenerateRevenueReportAction.php`
- Modify: `app/Actions/Finance/CalculateCustomerMetricsAction.php`
- Modify: `app/Actions/Finance/CalculateExpansionReadinessAction.php`
- Modify: `app/Actions/Analytics/GenerateProductAnalyticsAction.php`
- Modify: `app/Actions/Finance/CalculateBreakEvenAction.php`

- [ ] **Step 1: Replace `DATE_FORMAT` and `DATE()` with `db_date_format()`**
Open the action files listed above. Search for `DATE_FORMAT` and `DATE(`.
Replace them with the dynamic helper.

```php
// Example transformation
// Before: 
// ->selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period")

// After:
$dateFormatSql = db_date_format('created_at', $dateFormat);
// ->selectRaw("{$dateFormatSql} as period")
```

- [ ] **Step 2: Replace `YEAR()` and `MONTH()`**
Open `CalculateExpansionReadinessAction.php`. Replace native MySQL extraction with standard Laravel where clauses or `db_date_format()`.

- [ ] **Step 3: Fix Correlated Subquery in `CalculateBreakEvenAction`**
Open `CalculateBreakEvenAction.php`. Replace the `DB::raw` subquery inside the `sum()` with a proper join.

```php
// Before:
// ->sum(DB::raw('quantity * (SELECT cost FROM products WHERE products.id = sale_details.product_id)'));

// After:
// ->join('products', 'products.id', '=', 'sale_details.product_id')
// ->sum(DB::raw('sale_details.quantity * products.cost'));
```

- [ ] **Step 4: Commit**
```bash
git add app/Actions/
git commit -m "fix: abstract MySQL-specific raw queries for SQLite NativePHP compatibility"
```

### Task 2: Migrate `.env` Configurations to Database-Backed Settings

**Files:**
- Create: `database/migrations/YYYY_MM_DD_add_mail_settings_to_settings_table.php`
- Modify: `app/Models/Setting.php`
- Modify: `app/Services/MailConfigService.php`
- Modify: `app/Livewire/Settings/Smtp.php`
- Modify: `app/Providers/AppServiceProvider.php`

- [ ] **Step 1: Create Migration**
Run `php artisan make:migration add_mail_settings_to_settings_table`.
Add nullable string columns for `smtp_host`, `smtp_port`, `smtp_username`, `smtp_password`, `smtp_encryption`, `mail_from_address`.

- [ ] **Step 2: Update `Setting` Model**
Add the new columns to the `$fillable` array in `app/Models/Setting.php`.

- [ ] **Step 3: Refactor `MailConfigService` and `Smtp` Component**
Change `MailConfigService::updateEnv()` to instead update the `Setting::first()` record.
Update `Smtp.php` to read and write from the `Setting` model instead of `config('mail...')` or `.env`.

- [ ] **Step 4: Boot Settings dynamically**
Open `app/Providers/AppServiceProvider.php`. In the `boot()` method, retrieve the settings and override the Laravel config at runtime.

```php
// AppServiceProvider.php boot()
if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
    $settings = \App\Models\Setting::first();
    if ($settings && $settings->smtp_host) {
        config([
            'mail.mailers.smtp.host' => $settings->smtp_host,
            'mail.mailers.smtp.port' => $settings->smtp_port,
            'mail.mailers.smtp.username' => $settings->smtp_username,
            'mail.mailers.smtp.password' => $settings->smtp_password,
            'mail.mailers.smtp.encryption' => $settings->smtp_encryption,
            'mail.from.address' => $settings->mail_from_address,
        ]);
    }
}
```

- [ ] **Step 5: Commit**
```bash
git add database/migrations/ app/Models/Setting.php app/Services/MailConfigService.php app/Livewire/Settings/Smtp.php app/Providers/AppServiceProvider.php
git commit -m "feat: migrate read-only .env mail configurations to database-backed settings"
```

### Task 3: Build True Native OS Menu & SPA Event Communication

**Files:**
- Modify: `app/Providers/DesktopServiceProvider.php`
- Create: `app/Livewire/NativeEventListener.php`
- Modify: `resources/views/layouts/app.blade.php`

- [ ] **Step 1: Build Native Menu**
Open `app/Providers/DesktopServiceProvider.php`. Replace the web redirects with native event dispatchers.

```php
use Native\Laravel\Facades\Menu;
use Native\Laravel\Menu\Menu as NativeMenu;

public function boot(): void
{
    // ...
    Menu::new()
        ->appMenu()
        ->submenu('View', NativeMenu::new()
            ->event(\Native\Laravel\Events\App\WindowToggled::class, 'Toggle Fullscreen', 'CmdOrCtrl+F')
            ->event('native.navigate.dashboard', 'Dashboard', 'CmdOrCtrl+D')
            ->event('native.navigate.settings', 'Settings', 'CmdOrCtrl+,')
        )
        ->submenu('Data', NativeMenu::new()
            ->event('native.sync.trigger', 'Sync with Cloud', 'CmdOrCtrl+S')
        )
        ->register();
}
```

- [ ] **Step 2: Create Global Livewire Event Listener**
Create a hidden Livewire component `app/Livewire/NativeEventListener.php`.

```php
<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NativeEventListener extends Component
{
    #[On('native.navigate.dashboard')]
    public function goToDashboard() { return redirect()->route('dashboard')->navigate(); }

    #[On('native.navigate.settings')]
    public function goToSettings() { return redirect()->route('settings.index')->navigate(); }

    #[On('native.sync.trigger')]
    public function triggerSync() {
        // Trigger local sync service...
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Sync triggered!']);
    }

    public function render() { return <<<'HTML'
        <div style="display:none;"></div>
    HTML; }
}
```

- [ ] **Step 3: Include in Layout**
Open `resources/views/layouts/app.blade.php` and place `<livewire:native-event-listener />` near the bottom of the `<body>`.

- [ ] **Step 4: Commit**
```bash
git add app/Providers/DesktopServiceProvider.php app/Livewire/NativeEventListener.php resources/views/layouts/app.blade.php
git commit -m "feat: implement true native OS menu and global SPA event listener"
```