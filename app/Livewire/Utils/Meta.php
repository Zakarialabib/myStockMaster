<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Component;
use App\Traits\WithAlert;

class Meta extends Component
{
    use WithAlert;

    public function render()
    {
        return view('livewire.utils.meta');
    }
}
