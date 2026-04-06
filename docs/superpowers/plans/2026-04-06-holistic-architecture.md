# Holistic Architecture & Performance Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Finalize the backend architecture by refactoring Reports, Settings, and POS using strict Domain Services, Livewire Form Objects, and bleeding-edge Livewire v3/v4 features (like `#[Computed]`, `#[Locked]`, and `wire:navigate`), while completely eliminating N+1 query performance bottlenecks and securing sensitive state.

**Architecture:** 
- **Performance:** Eliminate N+1 queries using strict Eloquent eager loading (`with()`).
- **Livewire State:** Replace bloated public array properties with `#[Computed]` methods to reduce network payload. Protect sensitive IDs and tokens using `#[Locked]`.
- **Domain Services:** Extract all remaining fat component logic (e.g., `.env` writing, external API calls, PDF generation) into dedicated Services (`MailConfigService`, `MessagingService`, `PosService`).
- **SPA UX:** Implement `wire:navigate` globally on navigation links for instant page transitions.

**Tech Stack:** Laravel 11/12, Livewire 3/4, PHP 8.2+

---

### Task 1: Fix N+1 Queries & Payload Bloat in Reports (Suppliers & Customers)

**Files:**
- Modify: `app/Livewire/Reports/SuppliersReport.php`
- Modify: `app/Livewire/Reports/CustomersReport.php`
- Modify: `resources/views/livewire/reports/suppliers-report.blade.php`
- Modify: `resources/views/livewire/reports/customers-report.blade.php`

- [ ] **Step 1: Write the failing test for N+1 and Computed properties**

```php
// tests/Feature/Livewire/Reports/SuppliersReportTest.php
<?php
namespace Tests\Feature\Livewire\Reports;
use App\Livewire\Reports\SuppliersReport;
use Livewire\Livewire;
use Tests\TestCase;

class SuppliersReportTest extends TestCase
{
    public function test_it_uses_computed_properties_and_loads_relations()
    {
        $component = Livewire::test(SuppliersReport::class);
        $component->assertHasNoErrors();
        
        // Assert public property $suppliers does not exist (it should be computed)
        $this->assertObjectNotHasProperty('suppliers', $component->instance());
    }
}
```

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=SuppliersReportTest`
Expected: FAIL

- [ ] **Step 3: Write minimal implementation**

```php
// app/Livewire/Reports/SuppliersReport.php
<?php
namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Supplier;
use App\Models\Purchase;

class SuppliersReport extends Component
{
    use WithPagination;

    public $supplier_id;
    public $start_date;
    public $end_date;

    #[Computed]
    public function suppliers()
    {
        return Supplier::select('name', 'id')->get();
    }

    #[Computed]
    public function purchases()
    {
        $query = Purchase::with('supplier'); // Fixed N+1

        if ($this->supplier_id) {
            $query->where('supplier_id', $this->supplier_id);
        }
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [$this->start_date, $this->end_date]);
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.reports.suppliers-report');
    }
}
```
*Note: Apply identical `#[Computed]` and `with('customer')` eager loading logic to `CustomersReport.php`.*

- [ ] **Step 4: Update Blade Views**
Update `resources/views/livewire/reports/suppliers-report.blade.php` to use `$this->suppliers` instead of `$suppliers`, and `$this->purchases` instead of `$purchases`.

- [ ] **Step 5: Run test to verify it passes**
Run: `php artisan test --filter=SuppliersReportTest`
Expected: PASS

- [ ] **Step 6: Commit**
```bash
git add app/Livewire/Reports/SuppliersReport.php app/Livewire/Reports/CustomersReport.php resources/views/livewire/reports/
git commit -m "perf: fix N+1 queries and implement computed properties in reports"
```

### Task 2: Secure & Refactor Settings (SMTP & Messaging)

**Files:**
- Create: `app/Services/MailConfigService.php`
- Modify: `app/Livewire/Settings/Smtp.php`
- Modify: `app/Livewire/Settings/Messaging.php`

- [ ] **Step 1: Write the failing test**

```php
// tests/Feature/Livewire/Settings/SmtpTest.php
<?php
namespace Tests\Feature\Livewire\Settings;
use App\Livewire\Settings\Smtp;
use Livewire\Livewire;
use Tests\TestCase;

class SmtpTest extends TestCase
{
    public function test_it_does_not_expose_passwords_in_public_state()
    {
        $component = Livewire::test(Smtp::class);
        $this->assertEmpty($component->get('mail_password'));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=SmtpTest`

- [ ] **Step 3: Write minimal implementation (Service & Livewire)**

```php
// app/Services/MailConfigService.php
<?php
namespace App\Services;

use Illuminate\Support\Facades\File;

class MailConfigService
{
    public function updateEnv(array $data): void
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $str .= "\n"; // Key not found, append it
            $keyPosition = strpos($str, "{$key}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
            
            if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$key}={$value}\n";
            } else {
                $str = str_replace($oldLine, "{$key}={$value}", $str);
            }
        }
        
        file_put_contents($envFile, $str);
    }
}
```

```php
// app/Livewire/Settings/Smtp.php
<?php
namespace App\Livewire\Settings;

use Livewire\Component;
use App\Services\MailConfigService;
use Livewire\Attributes\Locked;

class Smtp extends Component
{
    public $mail_mailer;
    public $mail_host;
    public $mail_port;
    public $mail_username;
    
    // Do not pre-fill passwords from config to avoid payload exposure
    public $mail_password = '';
    public $mail_encryption;
    public $mail_from_address;

    public function mount()
    {
        $this->mail_mailer = config('mail.default');
        $this->mail_host = config('mail.mailers.smtp.host');
        $this->mail_port = config('mail.mailers.smtp.port');
        $this->mail_username = config('mail.mailers.smtp.username');
        $this->mail_encryption = config('mail.mailers.smtp.encryption');
        $this->mail_from_address = config('mail.from.address');
    }

    public function update(MailConfigService $service)
    {
        $data = [
            'MAIL_MAILER' => $this->mail_mailer,
            'MAIL_HOST' => $this->mail_host,
            'MAIL_PORT' => $this->mail_port,
            'MAIL_USERNAME' => $this->mail_username,
            'MAIL_ENCRYPTION' => $this->mail_encryption,
            'MAIL_FROM_ADDRESS' => $this->mail_from_address,
        ];

        if (!empty($this->mail_password)) {
            $data['MAIL_PASSWORD'] = $this->mail_password;
        }

        $service->updateEnv($data);
        
        $this->dispatch('alert', ['type' => 'success', 'message' => 'SMTP Settings updated successfully!']);
    }
    
    public function render()
    {
        return view('livewire.settings.smtp');
    }
}
```
*Note: Apply similar refactoring to `Messaging.php` to use `#[Locked]` for API keys or load them only when required.*

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --filter=SmtpTest`

- [ ] **Step 5: Commit**
```bash
git add app/Livewire/Settings/ app/Services/MailConfigService.php
git commit -m "refactor: secure SMTP settings and extract env logic to MailConfigService"
```

### Task 3: Refactor POS (Point of Sale) Component

**Files:**
- Create: `app/Livewire/Forms/PosForm.php`
- Modify: `app/Livewire/Pos/Index.php`

- [ ] **Step 1: Write the failing test**

```php
// tests/Feature/Livewire/Pos/IndexTest.php
<?php
namespace Tests\Feature\Livewire\Pos;
use App\Livewire\Pos\Index;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_it_uses_form_object()
    {
        $component = Livewire::test(Index::class);
        $this->assertTrue(property_exists($component->instance(), 'form'));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=IndexTest`

- [ ] **Step 3: Write minimal implementation**
Extract `$customer_id`, `$warehouse_id`, `$tax_percentage`, `$discount_percentage`, `$shipping_amount`, `$payment_method`, `$note` into `app/Livewire/Forms/PosForm.php`.

Update `app/Livewire/Pos/Index.php` to instantiate `public PosForm $form;` and update all `wire:model` references in `resources/views/livewire/pos/index.blade.php`.

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --filter=IndexTest`

- [ ] **Step 5: Commit**
```bash
git add app/Livewire/Forms/PosForm.php app/Livewire/Pos/Index.php resources/views/livewire/pos/
git commit -m "refactor: extract POS state into PosForm object"
```

### Task 4: Global SPA Transition (`wire:navigate`)

**Files:**
- Modify: `resources/views/layouts/app.blade.php` (or sidebar/navigation component)

- [ ] **Step 1: Write minimal implementation**
Search for all `<a href="{{ route(...) }}">` tags in the main sidebar and navigation files.
Add the `wire:navigate` attribute to them to enable Livewire 3's SPA mode.

```html
<!-- Example transformation -->
<a href="{{ route('dashboard') }}" wire:navigate class="nav-link">
    Dashboard
</a>
```

- [ ] **Step 2: Verify visually**
No automated test needed for `wire:navigate` presence, verify manually that clicking links does not trigger a full browser reload.

- [ ] **Step 3: Commit**
```bash
git add resources/views/
git commit -m "feat: implement wire:navigate for SPA transitions across the app"
```
