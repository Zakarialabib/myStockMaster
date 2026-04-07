<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsObserver
{
    public function updated(Setting $setting): void
    {
        // Refresh the cached list of settings
        Cache::forget('settings');
    }
}
