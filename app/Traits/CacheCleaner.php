<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Artisan;

trait CacheCleaner
{
    public static function bootCacheCleaner(): void
    {
        self::created(static function (): void {
            Artisan::call('cache:clear');
        });

        self::updated(static function (): void {
            Artisan::call('cache:clear');
        });

        self::deleted(static function (): void {
            Artisan::call('cache:clear');
        });
    }
}
