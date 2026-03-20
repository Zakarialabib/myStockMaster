<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\WithAlert;

new class extends Component
{
    use WithAlert;

    #[On('onClearCache')]
    public function onClearCache(): void
    {
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize');

        $this->alert('success', __('All caches have been cleared!'));
    }
};
?>

<div>
    <button type="button" wire:click="onClearCache" wire:loading.attr="disabled" class="w-full text-left">
        {{ __('Clear all Cache') }}
    </button>
</div>
