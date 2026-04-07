<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NativeEventListener extends Component
{
    #[On('native.navigate.dashboard')]
    public function goToDashboard()
    {
        return redirect()->route('dashboard')->navigate();
    }

    #[On('native.navigate.settings')]
    public function goToSettings()
    {
        return redirect()->route('settings.index')->navigate();
    }

    #[On('native.sync.trigger')]
    public function triggerSync()
    {
        // Trigger local sync service...
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Sync triggered!']);
    }

    public function render()
    {
        return <<<'HTML'
            <div style="display:none;"></div>
        HTML;
    }
}
