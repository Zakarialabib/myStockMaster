<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Component;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

#[Layout('layouts.components.dashboard')]
class Sidebar extends Component
{
    public function render(): View
    {
        return view('livewire.utils.sidebar');
    }
}
