<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Traits\WithAlert;
use Livewire\Component;

class Meta extends Component
{
    use WithAlert;

    public function render()
    {
        return view('livewire.utils.meta');
    }
}
