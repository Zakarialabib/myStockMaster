<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Cache extends Component
{
    use LivewireAlert;

    protected $listeners = ['onClearCache'];

    public function render()
    {
        return view('livewire.cache');
    }

    public function onClearCache()
    {
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize');

        $this->alert('success', __('All caches have been cleared!'));
    }
}
