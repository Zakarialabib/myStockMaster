<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Illuminate\Support\Facades\Artisan;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;

class Cache extends Component
{
    use LivewireAlert;

    public function render()
    {
        return view('livewire.utils.cache');
    }

    #[On('onClearCache')]
    public function onClearCache()
    {
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize');

        $this->alert('success', __('All caches have been cleared!'));
    }
}
