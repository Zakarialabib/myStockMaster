<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SyncService
{
    protected string $cloudUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->cloudUrl = config('services.mystockmaster.cloud_url');
        $this->apiToken = config('services.mystockmaster.api_token');
    }

    /** Execute the synchronization process. */
    public function sync(): array
    {
        // Only run sync if we are in NativePHP/Desktop mode or explicitly requested
        // For now, we assume this is triggered manually or via job

        $lastSyncedAt = cache('last_synced_at');

        try {
            // 1. Pull Changes from Cloud
            if ($this->cloudUrl && $this->apiToken) {
                $response = Http::withToken($this->apiToken)
                    ->get($this->cloudUrl.'/api/sync/pull', [
                        'last_synced_at' => $lastSyncedAt,
                    ]);

                if ($response->successful()) {
                    $this->applyRemoteChanges($response->json());
                } else {
                    Log::error('Sync Pull Failed', ['status' => $response->status(), 'body' => $response->body()]);

                    return ['status' => 'error', 'message' => 'Failed to pull changes from cloud.'];
                }
            }

            // 2. Push Local Changes to Cloud (TODO: Implement dirty tracking)
            // $this->pushLocalChanges();

            // Update timestamp
            cache(['last_synced_at' => now()->toIso8601String()]);

            return ['status' => 'success', 'message' => 'Sync completed successfully.'];
        } catch (Exception $e) {
            Log::error('Sync Exception: '.$e->getMessage());

            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /** Apply changes received from the cloud. */
    protected function applyRemoteChanges(array $data): void
    {
        DB::transaction(function () use ($data) {
            if (isset($data['products'])) {
                foreach ($data['products'] as $item) {
                    Product::updateOrCreate(['id' => $item['id']], $item);
                }
            }

            if (isset($data['customers'])) {
                foreach ($data['customers'] as $item) {
                    Customer::updateOrCreate(['id' => $item['id']], $item);
                }
            }

            // Add other models as needed
        });
    }
}
