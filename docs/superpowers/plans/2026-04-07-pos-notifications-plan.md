# POS Optimizations & Smart Notifications Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement POS post-checkout settings (A4 vs Thermal vs Auto-Print), smart notification routing (Email + WhatsApp based on customer data), and brand color theming for the Notification Logs.

**Architecture:** 
1. Database: Add `pos_post_checkout_action` to settings.
2. POS UI: Update `App\Livewire\Pos\Index` to dispatch browser events to open invoice preview modals after a successful checkout, respecting the setting.
3. Observers: Update `SaleObserver` and `SalePaymentObserver` to check customer `email` and `phone` attributes, and dynamically dispatch to the appropriate channels.
4. Logs UI: Update the `notification-logs.blade.php` to use CSS variables from `mail_styles['primary_color']`.

**Tech Stack:** Laravel Livewire, Alpine.js, Laravel Observers.

---

### Task 1: Database Setup for POS Settings

**Files:**
- Create: `database/migrations/YYYY_MM_DD_HHMMSS_add_pos_settings_to_settings_table.php`
- Modify: `app/Models/Setting.php`
- Modify: `app/Livewire/Forms/SettingForm.php`
- Modify: `resources/views/livewire/settings/index.blade.php`

- [ ] **Step 1: Create the migration**

Run: `php artisan make:migration add_pos_settings_to_settings_table`

Modify the generated file:
```php
public function up(): void
{
    Schema::table('settings', function (Blueprint $table) {
        $table->string('pos_post_checkout_action')->default('preview_a4');
    });
}

public function down(): void
{
    Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn('pos_post_checkout_action');
    });
}
```

- [ ] **Step 2: Update the Setting model**

Modify `app/Models/Setting.php`:
```php
// In the $fillable array, add:
'pos_post_checkout_action',
```

- [ ] **Step 3: Update SettingForm**

Modify `app/Livewire/Forms/SettingForm.php`:
```php
public ?string $pos_post_checkout_action = 'preview_a4';

// In init():
$this->pos_post_checkout_action = $settings->pos_post_checkout_action ?? 'preview_a4';
```

- [ ] **Step 4: Update Settings UI**

Modify `resources/views/livewire/settings/index.blade.php`.
Inside `x-show="subtab === 'system'"` (or wherever the System Config is), add the dropdown:
```blade
<div class="mt-6 pt-6 border-t border-gray-200">
    <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('POS Settings') }}</h3>
    <div class="w-full md:w-1/2">
        <x-label for="pos_post_checkout_action" :value="__('Post-Checkout Action')" />
        <x-select wire:model.live="form.pos_post_checkout_action" id="pos_post_checkout_action" name="pos_post_checkout_action" class="block w-full">
            <option value="preview_a4">{{ __('Show A4 Invoice Preview') }}</option>
            <option value="preview_thermal">{{ __('Show Thermal Receipt Preview') }}</option>
            <option value="auto_print_thermal">{{ __('Auto-Print Thermal Receipt') }}</option>
        </x-select>
    </div>
</div>
```

- [ ] **Step 5: Commit**

```bash
git add database/migrations/ app/Models/Setting.php app/Livewire/Forms/SettingForm.php resources/views/livewire/settings/index.blade.php
git commit -m "feat(pos): add pos_post_checkout_action setting"
```

---

### Task 2: Implement POS Checkout Logic

**Files:**
- Modify: `app/Livewire/Pos/Index.php`
- Modify: `resources/views/livewire/pos/index.blade.php`

- [ ] **Step 1: Dispatch browser events from POS Controller**

Modify `app/Livewire/Pos/Index.php`. Find the `checkout()` or `store()` method where the sale is successfully finalized.
After creating the sale, instead of just resetting the cart, add:
```php
$settings = \App\Models\Setting::first();
$action = $settings->pos_post_checkout_action ?? 'preview_a4';

$this->dispatch('checkout-completed', [
    'sale_id' => $sale->id,
    'action' => $action
]);
```

- [ ] **Step 2: Handle Alpine.js modal in POS View**

Modify `resources/views/livewire/pos/index.blade.php`.
Add an Alpine component to listen to the event and display the iframe:
```blade
<div x-data="{ 
        showModal: false, 
        saleId: null, 
        action: 'preview_a4',
        iframeUrl: ''
    }" 
    @checkout-completed.window="
        saleId = $event.detail[0].sale_id;
        action = $event.detail[0].action;
        iframeUrl = (action === 'preview_a4') 
            ? '/sales/pdf/' + saleId 
            : '/sales/pos-receipt/' + saleId;
            
        if (action === 'auto_print_thermal') {
            let printFrame = document.createElement('iframe');
            printFrame.style.display = 'none';
            printFrame.src = iframeUrl;
            document.body.appendChild(printFrame);
            printFrame.onload = function() {
                printFrame.contentWindow.print();
            };
        } else {
            showModal = true;
        }
    ">
    
    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('Invoice Preview') }}
                            </h3>
                            <div class="mt-4 h-96">
                                <iframe :src="iframeUrl" class="w-full h-full border-0 rounded"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="document.querySelector('iframe').contentWindow.print()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Print') }}
                    </button>
                    <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Pos/Index.php resources/views/livewire/pos/index.blade.php
git commit -m "feat(pos): implement smart checkout previews and auto-print"
```

---

### Task 3: Smart Notification Routing (Email + WhatsApp)

**Files:**
- Modify: `app/Observers/SaleObserver.php`
- Modify: `app/Observers/SalePaymentObserver.php`

- [ ] **Step 1: Update SaleObserver**

Modify `app/Observers/SaleObserver.php`:
```php
public function created(Sale $sale): void
{
    $settings = Setting::first();
    $triggers = $settings?->notification_triggers ?? [];
    $activeChannels = $triggers['sale_created'] ?? [];

    if ($sale->customer) {
        if (in_array('mail', $activeChannels) && !empty($sale->customer->email)) {
            Notification::route('mail', $sale->customer->email)
                ->notify(new SaleNotification($sale, 'mail'));
        }

        if (in_array('whatsapp', $activeChannels) && !empty($sale->customer->phone)) {
            // If you have a real WhatsApp channel configured in Laravel, use 'whatsapp'. 
            // For now, we simulate logging it by passing 'whatsapp' to the Notification.
            Notification::route('whatsapp', $sale->customer->phone)
                ->notify(new SaleNotification($sale, 'whatsapp'));
        }
    }
}
```

- [ ] **Step 2: Update SalePaymentObserver**

Modify `app/Observers/SalePaymentObserver.php`:
```php
public function created(SalePayment $payment): void
{
    $settings = Setting::first();
    $triggers = $settings?->notification_triggers ?? [];
    $activeChannels = $triggers['payment_received'] ?? [];

    if ($payment->sale && $payment->sale->customer) {
        if (in_array('mail', $activeChannels) && !empty($payment->sale->customer->email)) {
            Notification::route('mail', $payment->sale->customer->email)
                ->notify(new PaymentSaleNotification($payment, 'mail'));
        }

        if (in_array('whatsapp', $activeChannels) && !empty($payment->sale->customer->phone)) {
            Notification::route('whatsapp', $payment->sale->customer->phone)
                ->notify(new PaymentSaleNotification($payment, 'whatsapp'));
        }
    }
}
```

- [ ] **Step 3: Update Settings UI for WhatsApp Checkbox**

Modify `resources/views/livewire/settings/index.blade.php`.
Inside the Triggers tab (`x-show="subtab === 'triggers'"`), add the WhatsApp checkbox next to Email for both events:
```blade
<!-- Under Sale Created -->
<label class="inline-flex items-center cursor-pointer ml-4">
    <input type="checkbox" wire:model.live="form.notification_triggers.sale_created" value="whatsapp" class="sr-only peer">
    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-green-500"></div>
    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">WhatsApp</span>
</label>

<!-- Under Payment Received -->
<label class="inline-flex items-center cursor-pointer ml-4">
    <input type="checkbox" wire:model.live="form.notification_triggers.payment_received" value="whatsapp" class="sr-only peer">
    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-green-500"></div>
    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">WhatsApp</span>
</label>
```

- [ ] **Step 4: Commit**

```bash
git add app/Observers/SaleObserver.php app/Observers/SalePaymentObserver.php resources/views/livewire/settings/index.blade.php
git commit -m "feat(notifications): implement smart routing for email and whatsapp"
```

---

### Task 4: Dynamic Notification Logs Theming

**Files:**
- Modify: `resources/views/livewire/settings/notification-logs.blade.php`

- [ ] **Step 1: Inject CSS Variables & Update Badges**

Modify `resources/views/livewire/settings/notification-logs.blade.php`:
```blade
@php
    $mailStyles = \App\Models\Setting::first()?->mail_styles ?? ['primary_color' => '#4f46e5'];
    $primaryColor = $mailStyles['primary_color'];
@endphp
<div style="--theme-primary: {{ $primaryColor }}; --theme-primary-light: {{ $primaryColor }}33;">
    <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Notification History') }}</h2>
    
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <!-- headers -->
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    @php $data = json_decode($log->data, true); @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <!-- other tds -->
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                  style="background-color: var(--theme-primary-light); color: var(--theme-primary);">
                                {{ $data['channel'] ?? 'database' }}
                            </span>
                        </td>
                        <!-- other tds -->
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/livewire/settings/notification-logs.blade.php
git commit -m "style(settings): apply brand color theming to notification logs"
```
