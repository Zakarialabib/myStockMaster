<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Logs extends Component
{
    public function render()
    {
        $logs = File::files(storage_path('logs'));

        return view('livewire.utils.logs', ['logs' => $logs]);
    }
}
