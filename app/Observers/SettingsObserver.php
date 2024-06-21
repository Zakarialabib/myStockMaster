<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsObserver
{
    /**
     * @param Setting $settings
     * @return void
     */
    public function updated(Setting $settings)
    {
        // Refresh the cached list of settings
        Cache::forget('settings');
    }
}
