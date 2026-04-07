# Visual Email Templates Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a visual email template customizer in the Settings UI that allows users to dynamically edit the HTML/styles of system emails.

**Architecture:** We will create a `mail_templates` configuration inside the `settings` table to store colors and typography overrides. A new Livewire component `VisualEmailTemplates` will allow editing these settings with a live preview iframe. The base Blade layout for emails (`resources/views/vendor/mail/html/themes/default.css` or custom layout) will read these settings.

**Tech Stack:** Laravel Livewire, Tailwind CSS, Alpine.js, Laravel Mail.

---

### Task 1: Database Setup for Email Styles

**Files:**
- Create: `database/migrations/YYYY_MM_DD_HHMMSS_add_mail_styles_to_settings_table.php`
- Modify: `app/Models/Setting.php`

- [ ] **Step 1: Create the migration**

Run: `php artisan make:migration add_mail_styles_to_settings_table`

Modify the generated file:
```php
public function up(): void
{
    Schema::table('settings', function (Blueprint $table) {
        $table->json('mail_styles')->nullable();
    });
}

public function down(): void
{
    Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn('mail_styles');
    });
}
```

- [ ] **Step 2: Update the Setting model**

Modify `app/Models/Setting.php` to add the cast and fillable property:
```php
// In the $fillable array, add:
'mail_styles',

// In the casts() method (or $casts array), add:
'mail_styles' => 'array',
```

- [ ] **Step 3: Commit**

```bash
git add database/migrations/ app/Models/Setting.php
git commit -m "feat(settings): add mail_styles column to settings table"
```

---

### Task 4: Create VisualEmailTemplates Livewire Component

**Files:**
- Create: `app/Livewire/Settings/VisualEmailTemplates.php`
- Create: `resources/views/livewire/settings/visual-email-templates.blade.php`
- Modify: `resources/views/livewire/settings/index.blade.php`

- [ ] **Step 1: Create Component Class**

Create `app/Livewire/Settings/VisualEmailTemplates.php`:
```php
<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Setting;
use App\Traits\WithAlert;
use Livewire\Component;

class VisualEmailTemplates extends Component
{
    use WithAlert;

    public array $mailStyles = [
        'primary_color' => '#4f46e5',
        'text_color' => '#374151',
        'background_color' => '#f3f4f6',
        'button_radius' => 'rounded-md',
    ];

    public function mount(): void
    {
        $settings = Setting::first();
        if ($settings && $settings->mail_styles) {
            $this->mailStyles = array_merge($this->mailStyles, $settings->mail_styles);
        }
    }

    public function updatedMailStyles(): void
    {
        $settings = Setting::first();
        $settings->update(['mail_styles' => $this->mailStyles]);
        
        $this->alert('success', __('Email template styles updated successfully!'));
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.settings.visual-email-templates');
    }
}
```

- [ ] **Step 2: Create Component View**

Create `resources/views/livewire/settings/visual-email-templates.blade.php`:
```blade
<div class="grid grid-cols-1 md:grid-cols-12 gap-6">
    <!-- Controls Sidebar -->
    <div class="md:col-span-4 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Brand Colors') }}</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Primary Color') }}</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" wire:model.live="mailStyles.primary_color" class="h-8 w-8 rounded cursor-pointer border-0 p-0">
                        <input type="text" wire:model.live="mailStyles.primary_color" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Text Color') }}</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" wire:model.live="mailStyles.text_color" class="h-8 w-8 rounded cursor-pointer border-0 p-0">
                        <input type="text" wire:model.live="mailStyles.text_color" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Background Color') }}</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" wire:model.live="mailStyles.background_color" class="h-8 w-8 rounded cursor-pointer border-0 p-0">
                        <input type="text" wire:model.live="mailStyles.background_color" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Preview -->
    <div class="md:col-span-8">
        <div class="bg-gray-100 rounded-lg p-8 h-full flex items-center justify-center border border-gray-200">
            <!-- Simulated Email Container -->
            <div class="w-full max-w-lg bg-white rounded shadow-md overflow-hidden" style="background-color: {{ $mailStyles['background_color'] }}">
                <!-- Header -->
                <div class="px-6 py-4 text-center" style="background-color: {{ $mailStyles['primary_color'] }}">
                    <h2 class="text-2xl font-bold text-white">{{ settings()?->company_name ?? 'Your Company' }}</h2>
                </div>
                <!-- Body -->
                <div class="px-6 py-8 bg-white">
                    <h3 class="text-xl font-bold mb-4" style="color: {{ $mailStyles['text_color'] }}">Your Invoice is Ready</h3>
                    <p class="mb-6" style="color: {{ $mailStyles['text_color'] }}">Hello John Doe,</p>
                    <p class="mb-6" style="color: {{ $mailStyles['text_color'] }}">Thank you for your recent purchase. Your invoice #INV-001 is attached to this email.</p>
                    <div class="text-center">
                        <a href="#" class="inline-block px-6 py-3 font-medium text-white {{ $mailStyles['button_radius'] }}" style="background-color: {{ $mailStyles['primary_color'] }}; text-decoration: none;">View Invoice</a>
                    </div>
                </div>
                <!-- Footer -->
                <div class="px-6 py-4 text-center border-t border-gray-200 bg-gray-50">
                    <p class="text-xs text-gray-500">© {{ date('Y') }} {{ settings()?->company_name }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>
```

- [ ] **Step 3: Register Sub-tab in index.blade.php**

Modify `resources/views/livewire/settings/index.blade.php`:
Add the sub-tab button under Notifications:
```blade
<button type="button" @click="subtab = 'email_templates'" :class="{ 'text-indigo-600 font-medium': subtab === 'email_templates', 'text-gray-600 hover:text-indigo-500': subtab !== 'email_templates' }" class="pl-10 pr-4 py-2 text-sm text-left transition-colors">{{ __('Visual Email Templates') }}</button>
```

Add the component to the center content area:
```blade
<div x-show="subtab === 'email_templates'">
    <livewire:settings.visual-email-templates />
</div>
```

- [ ] **Step 4: Commit**

```bash
git add app/Livewire/Settings/VisualEmailTemplates.php resources/views/livewire/settings/visual-email-templates.blade.php resources/views/livewire/settings/index.blade.php
git commit -m "feat(settings): create visual email templates livewire component"
```
