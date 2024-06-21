<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class UnderMaintenanceJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private $secret = null, private $refresh = false)
    {
    }

    public function handle(): void
    {
        if (settings('site_maintenance_status') === false) {
            Artisan::call('up');
        } else {
            Artisan::call('down', [
                '--secret'  => $this->secret,
                '--refresh' => $this->refresh,
            ]);
        }
    }
}
