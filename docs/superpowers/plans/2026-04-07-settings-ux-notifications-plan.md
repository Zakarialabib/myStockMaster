# Settings UX & Unified Notifications Hub Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Overhaul the Settings page into a 3-column layout with a Nested Sidebar and Dynamic Info Panel, and unify the Mail/Notifications system using Laravel's native `notifications` table.

**Architecture:** We will refactor the Livewire view into three columns. Alpine.js will handle the state for the dynamic info panel. We will add a `notification_triggers` JSON column to the `settings` table to store event toggles. We will also begin the migration of `App\Mail` classes to `App\Notifications` classes that use both `'mail'` and `'database'` channels to natively log all outbound communications without creating new tables.

**Tech Stack:** Laravel Livewire, Alpine.js, Tailwind CSS, Laravel Notifications.

---

### Task 1: Database Setup for Notification Triggers

**Files:**
- Create: `database/migrations/YYYY_MM_DD_HHMMSS_add_notification_triggers_to_settings_table.php`
- Modify: `app/Models/Setting.php`
- Modify: `app/Livewire/Forms/SettingForm.php`

- [ ] **Step 1: Create the migration**

Run: `php artisan make:migration add_notification_triggers_to_settings_table`

Modify the generated file:
```php
public function up(): void
{
    Schema::table('settings', function (Blueprint $table) {
        $table->json('notification_triggers')->nullable();
    });
}

public function down(): void
{
    Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn('notification_triggers');
    });
}
```

- [ ] **Step 2: Update the Setting model**

Modify `app/Models/Setting.php` to add the cast and fillable property:
```php
// In the $fillable array, add:
'notification_triggers',

// In the casts() method (or $casts array), add:
'notification_triggers' => 'array',
```

- [ ] **Step 3: Update SettingForm**

Modify `app/Livewire/Forms/SettingForm.php` to handle the new property:
```php
public ?array $notification_triggers = [];

// In init():
$this->notification_triggers = $settings->notification_triggers ?? [
    'sale_created' => ['mail', 'database'],
    'payment_received' => ['mail', 'database'],
];
```

- [ ] **Step 4: Commit**

```bash
git add database/migrations/ app/Models/Setting.php app/Livewire/Forms/SettingForm.php
git commit -m "feat(settings): add notification_triggers to settings table"
```

---

### Task 2: Layout Redesign (3-Column & Nested Sidebar)

**Files:**
- Modify: `resources/views/livewire/settings/index.blade.php`

- [ ] **Step 1: Implement 3-column Alpine.js layout**

Replace the main `div` wrapper inside `index.blade.php` with:
```blade
<div x-data="{ 
    tab: 'general', 
    subtab: 'company',
    infoTitle: '{{ __('Settings') }}', 
    infoDesc: '{{ __('Hover or click on any setting field to see detailed information about what it does and where it is used.') }}',
    infoPrivacy: ''
}" class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Sidebar (Nested) -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- General Section -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <button @click="tab = 'general'; subtab = 'company'" class="w-full px-4 py-3 text-left text-sm font-bold bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white flex items-center justify-between">
                        <span class="flex items-center space-x-2"><i class="fas fa-cog w-4 h-4"></i> <span>{{ __('General') }}</span></span>
                        <i class="fas fa-chevron-down text-xs" x-show="tab === 'general'"></i>
                    </button>
                    <div x-show="tab === 'general'" class="flex flex-col py-1">
                        <button @click="subtab = 'company'" :class="{ 'text-indigo-600 font-medium': subtab === 'company', 'text-gray-600 hover:text-indigo-500': subtab !== 'company' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">Company Info</button>
                        <button @click="subtab = 'system'" :class="{ 'text-indigo-600 font-medium': subtab === 'system', 'text-gray-600 hover:text-indigo-500': subtab !== 'system' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">System Config</button>
                        <button @click="subtab = 'siteConfig'" :class="{ 'text-indigo-600 font-medium': subtab === 'siteConfig', 'text-gray-600 hover:text-indigo-500': subtab !== 'siteConfig' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">Site Config</button>
                    </div>
                </div>
                <!-- Invoicing Section -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <button @click="tab = 'invoicing'; subtab = 'invoice'" class="w-full px-4 py-3 text-left text-sm font-bold bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white flex items-center justify-between">
                        <span class="flex items-center space-x-2"><i class="fas fa-file-invoice w-4 h-4"></i> <span>{{ __('Invoicing') }}</span></span>
                        <i class="fas fa-chevron-down text-xs" x-show="tab === 'invoicing'"></i>
                    </button>
                    <div x-show="tab === 'invoicing'" class="flex flex-col py-1">
                        <button @click="subtab = 'invoice'" :class="{ 'text-indigo-600 font-medium': subtab === 'invoice', 'text-gray-600 hover:text-indigo-500': subtab !== 'invoice' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">Templates & Prefixes</button>
                    </div>
                </div>
                <!-- Notifications Section -->
                <div>
                    <button @click="tab = 'notifications'; subtab = 'channels'" class="w-full px-4 py-3 text-left text-sm font-bold bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white flex items-center justify-between">
                        <span class="flex items-center space-x-2"><i class="fas fa-bell w-4 h-4"></i> <span>{{ __('Notifications') }}</span></span>
                        <i class="fas fa-chevron-down text-xs" x-show="tab === 'notifications'"></i>
                    </button>
                    <div x-show="tab === 'notifications'" class="flex flex-col py-1">
                        <button @click="subtab = 'channels'" :class="{ 'text-indigo-600 font-medium': subtab === 'channels', 'text-gray-600 hover:text-indigo-500': subtab !== 'channels' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">Delivery Channels</button>
                        <button @click="subtab = 'triggers'" :class="{ 'text-indigo-600 font-medium': subtab === 'triggers', 'text-gray-600 hover:text-indigo-500': subtab !== 'triggers' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">Event Triggers</button>
                        <button @click="subtab = 'logs'" :class="{ 'text-indigo-600 font-medium': subtab === 'logs', 'text-gray-600 hover:text-indigo-500': subtab !== 'logs' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">Notification Logs</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center Form Content -->
        <div class="lg:col-span-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <!-- Keep existing x-show="tab === 'company'" but change to x-show="subtab === 'company'" -->
                <!-- DO NOT DELETE EXISTING FORM FIELDS, just wrap them in the new x-show directives -->
                <div x-show="subtab === 'company'">
                    <!-- Existing company fields... -->
                </div>
            </div>
        </div>

        <!-- Right Info Panel -->
        <div class="lg:col-span-3">
            <div class="sticky top-24 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg shadow-sm border border-indigo-100 dark:border-indigo-800 p-6">
                <div class="flex items-center space-x-2 mb-4 text-indigo-700 dark:text-indigo-300">
                    <i class="fas fa-info-circle text-lg"></i>
                    <h3 class="text-lg font-bold" x-text="infoTitle"></h3>
                </div>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4" x-text="infoDesc"></p>
                <div x-show="infoPrivacy" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">
                    <i class="fas fa-shield-alt mr-1.5"></i>
                    <span x-text="infoPrivacy"></span>
                </div>
            </div>
        </div>
    </div>
</div>
```
*(Note: Ensure the existing form fields are migrated into the center column `x-show="subtab === '...'" ` blocks).*

- [ ] **Step 2: Commit**

```bash
git add resources/views/livewire/settings/index.blade.php
git commit -m "refactor(settings): implement 3-column nested layout and info panel"
```

---

### Task 3: Bind Dynamic Explanations to Inputs

**Files:**
- Modify: `resources/views/livewire/settings/index.blade.php`

- [ ] **Step 1: Add Alpine focus events**

For key fields (e.g., Company Name, Email, SMTP Host), add Alpine event listeners:
```blade
<x-input type="text" wire:model="form.company_name" id="company_name"
    @focus="infoTitle = 'Company Name'; infoDesc = 'Your official business name. This is displayed on the login screen, dashboard header, and is printed on all generated invoices, quotations, and reports.'; infoPrivacy = 'Public'"
/>
```
*(Apply similar `@focus` events to at least 5-10 major fields across Company Info, System Config, and Mail to demonstrate the UX).*

- [ ] **Step 2: Commit**

```bash
git add resources/views/livewire/settings/index.blade.php
git commit -m "feat(settings): bind dynamic explanations to form inputs"
```

---

### Task 4: Unified Notification Hub UI

**Files:**
- Modify: `resources/views/livewire/settings/index.blade.php`
- Modify: `resources/views/livewire/settings/smtp.blade.php`
- Modify: `resources/views/livewire/settings/messaging.blade.php`

- [ ] **Step 1: Add Channels View**

Inside `index.blade.php`, under `x-show="subtab === 'channels'"`, include the existing mail and messaging components:
```blade
<div x-show="subtab === 'channels'" class="space-y-8">
    <div>
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">SMTP Configuration</h2>
        <livewire:settings.smtp />
    </div>
    <div>
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Messaging (WhatsApp/Telegram)</h2>
        <livewire:settings.messaging />
    </div>
</div>
```

- [ ] **Step 2: Add Triggers View**

Inside `index.blade.php`, under `x-show="subtab === 'triggers'"`, build a simple toggle UI bound to `$form->notification_triggers`:
```blade
<div x-show="subtab === 'triggers'">
    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Automated Event Triggers</h2>
    <div class="space-y-4">
        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white">Sale Created</h4>
                <p class="text-sm text-gray-500">Send a notification when a new sale is completed.</p>
            </div>
            <div class="flex items-center space-x-4">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="form.notification_triggers.sale_created" value="mail" class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email</span>
                </label>
            </div>
        </div>
        <!-- Repeat for payment_received -->
    </div>
</div>
```

- [ ] **Step 3: Commit**

```bash
git add resources/views/livewire/settings/index.blade.php
git commit -m "feat(settings): create unified notifications hub UI"
```

---

### Task 5: Base Notification Refactoring

**Files:**
- Create: `app/Notifications/BaseSystemNotification.php`

- [ ] **Step 1: Create Base Notification Class**

Create a class that implements the `via` and `toDatabase` logic natively.
Run: `php artisan make:notification BaseSystemNotification`

Modify the generated file:
```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BaseSystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $subjectTitle;
    public string $channelType;

    public function __construct(string $subjectTitle, string $channelType = 'mail')
    {
        $this->subjectTitle = $subjectTitle;
        $this->channelType = $channelType;
    }

    public function via(object $notifiable): array
    {
        // Dynamically read from settings or default to provided channel + database
        return [$this->channelType, 'database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'subject' => $this->subjectTitle,
            'channel' => $this->channelType,
            'status' => 'sent',
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
```
*(This sets up the architectural foundation for refactoring all `App\Mail` classes into Notifications that natively log to the database).*

- [ ] **Step 2: Commit**

```bash
git add app/Notifications/BaseSystemNotification.php
git commit -m "feat(notifications): add base system notification class for unified logging"
```
