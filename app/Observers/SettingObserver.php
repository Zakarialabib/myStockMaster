<?php

namespace App\Observers;

use App\Models\Setting;

class SettingObserver
{
    /**
     * Handle the Setting "created" event.
     *
     * @param  \App\Models\Sameleon\Setting  $setting
     * @return void
     */
    public function created(Setting $setting)
    {
        $this->clearAllCache();
    }

    /**
     * Handle the Setting "updated" event.
     *
     * @param  \App\Models\Sameleon\Setting  $setting
     * @return void
     */
    public function updated(Setting $setting)
    {
        $this->clearAllCache();
    }

    /**
     * Handle the Setting "deleted" event.
     *
     * @param  \App\Models\Sameleon\Setting  $setting
     * @return void
     */
    public function deleted(Setting $setting)
    {
        $this->clearAllCache();
    }

    /**
     * Handle the Setting "restored" event.
     *
     * @param  \App\Models\Sameleon\Setting  $setting
     * @return void
     */
    public function restored(Setting $setting)
    {
        $this->clearAllCache();
    }

    /**
     * Handle the Setting "force deleted" event.
     *
     * @param  \App\Models\Sameleon\Setting  $setting
     * @return void
     */
    public function forceDeleted(Setting $setting)
    {
        $this->clearAllCache();
    }

    private function clearAllCache()
    {
        cache()->forget('settings');
    }
}
