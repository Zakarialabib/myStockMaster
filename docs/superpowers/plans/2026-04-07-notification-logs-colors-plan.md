# Notification Logs and Email Colors Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the UI to display the history of sent notifications from the database, and inject the selected Visual Email Template colors into the actual Blade Mail layout.

**Architecture:** We will create a `NotificationLogs` Livewire component containing a table to display records from the `notifications` table. We will also export the default Laravel Mail theme and modify its CSS file (`default.css`) to use dynamic CSS variables populated from the `mail_styles` settings during rendering.

**Tech Stack:** Laravel Livewire, Laravel Mail (Markdown).

---

### Task 1: Build Notification Logs UI

**Files:**
- Create: `app/Livewire/Settings/NotificationLogs.php`
- Create: `resources/views/livewire/settings/notification-logs.blade.php`
- Modify: `resources/views/livewire/settings/index.blade.php`

- [ ] **Step 1: Create the Livewire Component**

Create `app/Livewire/Settings/NotificationLogs.php`:
```php
<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationLogs extends Component
{
    use WithPagination;

    public function render(): \Illuminate\Contracts\View\View
    {
        // We query the default Laravel notifications table
        $logs = DB::table('notifications')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.settings.notification-logs', [
            'logs' => $logs
        ]);
    }
}
```

- [ ] **Step 2: Create the View**

Create `resources/views/livewire/settings/notification-logs.blade.php`:
```blade
<div>
    <h2 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Notification History') }}</h2>
    
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3">{{ __('Date') }}</th>
                    <th class="px-4 py-3">{{ __('Event Type') }}</th>
                    <th class="px-4 py-3">{{ __('Channel') }}</th>
                    <th class="px-4 py-3">{{ __('Subject / Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    @php
                        $data = json_decode($log->data, true);
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ class_basename($log->type) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                {{ $data['channel'] ?? 'database' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $data['subject'] ?? 'System Notification' }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Status: {{ $data['status'] ?? 'completed' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            {{ __('No notifications found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
```

- [ ] **Step 3: Register in Settings Index**

Modify `resources/views/livewire/settings/index.blade.php`.
Inside the `<!-- Center Form Content -->`, add the `x-show` block:
```blade
<div x-show="subtab === 'logs'">
    <livewire:settings.notification-logs />
</div>
```

- [ ] **Step 4: Commit**

```bash
git add app/Livewire/Settings/NotificationLogs.php resources/views/livewire/settings/notification-logs.blade.php resources/views/livewire/settings/index.blade.php
git commit -m "feat(settings): add notification logs data table"
```

---

### Task 2: Apply Visual Colors to Real Emails

**Files:**
- Create/Modify: `resources/views/vendor/mail/html/themes/default.css`
- Modify: `resources/views/vendor/mail/html/layout.blade.php`

- [ ] **Step 1: Publish Mail Vendor Views**

Run: `php artisan vendor:publish --tag=laravel-mail`

- [ ] **Step 2: Inject CSS Variables into Mail Layout**

Modify `resources/views/vendor/mail/html/layout.blade.php`.
Find the `<style>` tag section (or right before it) and inject the variables from the `mail_styles` setting. Since mailers run in jobs, we'll fetch settings directly.

```blade
@php
    $mailStyles = \App\Models\Setting::first()?->mail_styles ?? [
        'primary_color' => '#2d3748',
        'text_color' => '#718096',
        'background_color' => '#edf2f7',
    ];
@endphp
<style>
    :root {
        --mail-primary: {{ $mailStyles['primary_color'] }};
        --mail-text: {{ $mailStyles['text_color'] }};
        --mail-bg: {{ $mailStyles['background_color'] }};
    }
    /* Existing styles... */
</style>
```

- [ ] **Step 3: Modify Default Theme CSS**

Modify `resources/views/vendor/mail/html/themes/default.css` to use these variables.
*(Note: Because email clients are notoriously bad at CSS variables, a better approach for emails is to inject the values directly into the CSS rules within the Blade layout, or pass them to components).*

Instead of modifying `default.css`, let's modify the inline styles directly inside `resources/views/vendor/mail/html/layout.blade.php` and `resources/views/vendor/mail/html/header.blade.php`.

In `resources/views/vendor/mail/html/header.blade.php`, change the background color:
```blade
<tr>
<td class="header" style="background-color: {{ \App\Models\Setting::first()?->mail_styles['primary_color'] ?? '#2d3748' }};">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
```

In `resources/views/vendor/mail/html/layout.blade.php`, change the body background and text color wrappers:
```blade
<body style="background-color: {{ $mailStyles['background_color'] }}; color: {{ $mailStyles['text_color'] }};">
```

In `resources/views/vendor/mail/html/button.blade.php`, change the button background:
```blade
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
<a href="{{ $url }}" class="button button-{{ $color ?? 'primary' }}" target="_blank" rel="noopener" style="background-color: {{ \App\Models\Setting::first()?->mail_styles['primary_color'] ?? '#2d3748' }};">{{ $slot }}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
```

- [ ] **Step 4: Commit**

```bash
git add resources/views/vendor/mail/
git commit -m "feat(notifications): apply visual email templates to real emails"
```
