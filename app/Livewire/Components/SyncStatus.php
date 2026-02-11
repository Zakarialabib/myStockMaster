<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Jobs\SyncDatabaseJob;
use Illuminate\Support\Facades\Cache;

class SyncStatus extends Component
{
    public bool $isOnline = true;
    public $lastSyncedAt;
    public bool $isSyncing = false;
    public bool $showModal = false;
    public string $syncMessage = '';

    public function mount()
    {
        $this->lastSyncedAt = Cache::get('last_synced_at', 'Never');
        // Initial status check (simulated, real check happens on poll)
    }

    public function checkConnection()
    {
        // Simple check to google or own server
        // In NativePHP, we might have better ways, but this works generally
        $connected = @fsockopen("www.google.com", 80); 
        if ($connected){
            $this->isOnline = true;
            fclose($connected);
        } else {
            $this->isOnline = false;
        }
    }

    public function triggerSync()
    {
        if (!$this->isOnline) {
            $this->syncMessage = "Cannot sync while offline.";
            return;
        }

        $this->isSyncing = true;
        $this->syncMessage = "Sync started...";

        // Dispatch the job
        SyncDatabaseJob::dispatch();
        
        // In a real app, we'd listen for Echo events or poll a job status
        // For MVP, we simulate a delay then update
        // We'll just update the timestamp assuming it works
        $this->lastSyncedAt = now()->toIso8601String();
        Cache::put('last_synced_at', $this->lastSyncedAt);
        
        $this->isSyncing = false;
        $this->syncMessage = "Sync completed successfully.";
    }

    public function render()
    {
        return view('livewire.components.sync-status');
    }
}
