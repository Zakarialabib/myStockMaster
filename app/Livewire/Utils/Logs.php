<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Layout;
use App\Traits\WithAlert;

#[Layout('layouts.app')]
class Logs extends Component
{
    use WithAlert;

    public function render()
    {
        $logs = File::files(storage_path('logs'));

        return view('livewire.utils.logs', ['logs' => $logs]);
    }
}
