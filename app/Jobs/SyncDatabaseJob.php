<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\DatabaseSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncDatabaseJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public string $direction = 'both') {}

    public function handle(DatabaseSyncService $syncService): void
    {
        if ($this->direction === 'to-offline') {
            $syncService->syncToOffline();

            return;
        }

        if ($this->direction === 'to-online') {
            $syncService->syncToOnline();

            return;
        }

        $syncService->syncToOffline();
        $syncService->syncToOnline();
    }
}
