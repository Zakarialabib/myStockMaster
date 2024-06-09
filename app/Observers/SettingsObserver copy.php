<?php

declare(strict_types=1);

namespace App\Observers;

class SettingsObserver
{
    public function updated(): void
    {
        // Refresh the cached list of settings
        cache()->forget('settings');
    }
}
