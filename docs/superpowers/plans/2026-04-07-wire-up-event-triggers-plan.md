# Wire Up Event Triggers Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Connect the Notification Triggers toggles in the Settings UI to the actual Event Observers so that automated emails are only sent when enabled.

**Architecture:** We will create standard Laravel Observers (e.g., `SaleObserver`, `SalePaymentObserver`) that listen to the `created` event. These observers will check the `notification_triggers` setting. If the `mail` channel is enabled for that event, it will dispatch the respective Notification (e.g., `SaleNotification`) using `Notification::route()`.

**Tech Stack:** Laravel Observers, Laravel Notifications.

---

### Task 1: Wire Up Sale and Purchase Observers

**Files:**
- Modify: `app/Providers/AppServiceProvider.php` (if registering observers)
- Create: `app/Observers/SaleObserver.php`
- Create: `app/Observers/PurchaseObserver.php`

- [ ] **Step 1: Create SaleObserver**

Run: `php artisan make:observer SaleObserver --model=Sale`

Modify `app/Observers/SaleObserver.php`:
```php
<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Sale;
use App\Models\Setting;
use App\Notifications\SaleNotification;
use Illuminate\Support\Facades\Notification;

class SaleObserver
{
    public function created(Sale $sale): void
    {
        $settings = Setting::first();
        $triggers = $settings?->notification_triggers ?? [];

        // Check if 'mail' is in the array of active channels for 'sale_created'
        if (in_array('mail', $triggers['sale_created'] ?? [])) {
            if ($sale->customer && $sale->customer->email) {
                Notification::route('mail', $sale->customer->email)
                    ->notify(new SaleNotification($sale));
            }
        }
    }
}
```

- [ ] **Step 2: Create PurchaseObserver**

Run: `php artisan make:observer PurchaseObserver --model=Purchase`

Modify `app/Observers/PurchaseObserver.php`:
```php
<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Purchase;
use App\Models\Setting;
use App\Notifications\PurchaseNotification;
use Illuminate\Support\Facades\Notification;

class PurchaseObserver
{
    public function created(Purchase $purchase): void
    {
        $settings = Setting::first();
        $triggers = $settings?->notification_triggers ?? [];

        if (in_array('mail', $triggers['purchase_created'] ?? [])) {
            if ($purchase->supplier && $purchase->supplier->email) {
                Notification::route('mail', $purchase->supplier->email)
                    ->notify(new PurchaseNotification($purchase));
            }
        }
    }
}
```

- [ ] **Step 3: Register Observers**

Modify `app/Providers/AppServiceProvider.php` to register these observers in the `boot` method:
```php
use App\Models\Sale;
use App\Models\Purchase;
use App\Observers\SaleObserver;
use App\Observers\PurchaseObserver;

// Inside boot():
Sale::observe(SaleObserver::class);
Purchase::observe(PurchaseObserver::class);
```

- [ ] **Step 4: Commit**

```bash
git add app/Observers/SaleObserver.php app/Observers/PurchaseObserver.php app/Providers/AppServiceProvider.php
git commit -m "feat(notifications): wire up sale and purchase event triggers"
```

---

### Task 2: Wire Up Payment Observers

**Files:**
- Create: `app/Observers/SalePaymentObserver.php`
- Modify: `app/Providers/AppServiceProvider.php`

- [ ] **Step 1: Create SalePaymentObserver**

Run: `php artisan make:observer SalePaymentObserver --model=SalePayment`

Modify `app/Observers/SalePaymentObserver.php`:
```php
<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SalePayment;
use App\Models\Setting;
use App\Notifications\PaymentSaleNotification;
use Illuminate\Support\Facades\Notification;

class SalePaymentObserver
{
    public function created(SalePayment $payment): void
    {
        $settings = Setting::first();
        $triggers = $settings?->notification_triggers ?? [];

        if (in_array('mail', $triggers['payment_received'] ?? [])) {
            if ($payment->sale && $payment->sale->customer && $payment->sale->customer->email) {
                Notification::route('mail', $payment->sale->customer->email)
                    ->notify(new PaymentSaleNotification($payment));
            }
        }
    }
}
```

- [ ] **Step 2: Register Observer**

Modify `app/Providers/AppServiceProvider.php`:
```php
use App\Models\SalePayment;
use App\Observers\SalePaymentObserver;

// Inside boot():
SalePayment::observe(SalePaymentObserver::class);
```

- [ ] **Step 3: Update Settings UI for these triggers**

Modify `resources/views/livewire/settings/index.blade.php` inside the `subtab === 'triggers'` section to ensure the checkboxes match the array keys:
```blade
<!-- Sale Created -->
<input type="checkbox" wire:model.live="form.notification_triggers.sale_created" value="mail" class="sr-only peer">

<!-- Purchase Created -->
<input type="checkbox" wire:model.live="form.notification_triggers.purchase_created" value="mail" class="sr-only peer">

<!-- Payment Received -->
<input type="checkbox" wire:model.live="form.notification_triggers.payment_received" value="mail" class="sr-only peer">
```
*(Make sure to build out the HTML structure for the 'Purchase Created' and 'Payment Received' toggles similar to what was done in the previous plan for 'Sale Created')*

- [ ] **Step 4: Commit**

```bash
git add app/Observers/SalePaymentObserver.php app/Providers/AppServiceProvider.php resources/views/livewire/settings/index.blade.php
git commit -m "feat(notifications): wire up payment event triggers and update settings UI"
```
