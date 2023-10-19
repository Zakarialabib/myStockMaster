<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DetectPlatformServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        if ($this->nativephpIsDetected()) {

            config(['database.default' => 'nativephp']);
        } else {
            config(['database.default' => 'mysql']);
        }
    }


    /**
     * @return bool
     */
    private function nativephpIsDetected()
    {
        return class_exists("\Native\Laravel\NativeServiceProvider");
    }
}
