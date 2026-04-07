<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NativeEventListener extends Component
{
    #[On('native.navigate.dashboard')]
    public function goToDashboard()
    {
        return to_route('dashboard')->navigate();
    }

    #[On('native.navigate.settings')]
    public function goToSettings()
    {
        return to_route('settings.index')->navigate();
    }

    #[On('native.sync.trigger')]
    public function triggerSync(): void
    {
        // Trigger local sync service...
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Sync triggered!']);
    }

    public function render(): string
    {
        return <<<'HTML'
            <div style="display:none;"></div>
        HTML;
    }
}
