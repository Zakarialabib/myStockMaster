<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Traits\WithAlert;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
#[Lazy]
class Dashboard extends Component
{
    use WithAlert;

    public string $startDate;

    public string $endDate;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
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

    public function placeholder()
    {
        return view('livewire.placeholders.skeleton');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
