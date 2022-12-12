<?php

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsObserver
{
   
    public function updated(Setting $settings)
    {
        // Refresh the cached list of settings
            Cache::forget('settings');
    }

}
