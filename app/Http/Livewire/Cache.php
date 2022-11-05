<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;

class Cache extends Component
{
    protected $listeners = ['onClearCache'];

    public function render()
    {
        return view('livewire.cache');
    }

    public function onClearCache()
    {
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');

        Artisan::call('migrate:fresh --seed');
        
        Artisan::call('optimize');

        $this->alert('success', __('All caches have been cleared!') );

    }
}
