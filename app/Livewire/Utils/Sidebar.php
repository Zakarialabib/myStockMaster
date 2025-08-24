<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Component;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use App\Traits\WithAlert;

#[Layout('layouts.components.dashboard')]
class Sidebar extends Component
{
    use WithAlert;

    public function render(): View
    {
        return view('livewire.utils.sidebar');
    }
}
