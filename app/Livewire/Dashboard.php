<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Traits\WithAlert;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]

class Dashboard extends Component
{
    use WithAlert;

    public string $startDate;

    public string $endDate;

    public function mount(): void
    {
        $this->startDate = \Illuminate\Support\Facades\Date::now()->startOfMonth()->toDateString();
        $this->endDate = \Illuminate\Support\Facades\Date::now()->endOfMonth()->toDateString();
    }

    #[On('dashboard-date-range-updated')]
    public function updateDateRange(string $startDate, string $endDate): void
    {
        if ($startDate > $endDate) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.dashboard');
    }
}
