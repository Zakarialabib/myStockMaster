<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Traits\WithAlert;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.components.dashboard')]
class Sidebar extends Component
{
    use WithAlert;

    public function render(): View
    {
        return view('livewire.utils.sidebar');
    }
}
