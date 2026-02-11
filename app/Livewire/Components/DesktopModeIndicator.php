<?php

namespace App\Livewire\Components;

use App\Services\EnvironmentService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class DesktopModeIndicator extends Component
{
    public $isDesktop = false;
    public $isOffline = false;
    public $lastSync = null;
    public $showDetails = false;

    public function mount()
    {
        $this->isDesktop = EnvironmentService::isDesktop();
        $this->isOffline = EnvironmentService::isOffline();
        $this->lastSync = Cache::get('database_sync.last_sync');
    }

    public function render()
    {
        return view('livewire.components.desktop-mode-indicator');
    }

    /**
     * Toggle details visibility.
     */
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

    /**
     * Navigate to database sync page.
     */
    public function goToSync()
    {
        return redirect()->route('admin.database-sync');
    }

    /**
     * Refresh the component data.
     */
    public function refresh()
    {
        $this->isDesktop = EnvironmentService::isDesktop();
        $this->isOffline = EnvironmentService::isOffline();
        $this->lastSync = Cache::get('database_sync.last_sync');
    }
}