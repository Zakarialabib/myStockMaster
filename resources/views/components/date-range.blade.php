<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component
{
    public string $dispatchEvent = 'date-range-updated';

    #[Url(as: 'from', history: true, keep: true)]
    public string $startDate = '';

    #[Url(as: 'to', history: true, keep: true)]
    public string $endDate = '';

    public function mount(): void
    {
        if ($this->startDate === '') {
            $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        }

        if ($this->endDate === '') {
            $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        }

        if ($this->startDate > $this->endDate) {
            [$this->startDate, $this->endDate] = [$this->endDate, $this->startDate];
        }
    }

    public function initDates(): void
    {
        if ($this->dispatchEvent === '') {
            return;
        }

        $this->dispatch($this->dispatchEvent, startDate: $this->startDate, endDate: $this->endDate);
    }

    public function updatedStartDate(string $value): void
    {
        $this->startDate = $value;

        if ($this->startDate > $this->endDate) {
            $this->endDate = $this->startDate;
        }

        $this->initDates();
    }

    public function updatedEndDate(string $value): void
    {
        $this->endDate = $value;

        if ($this->startDate > $this->endDate) {
            $this->startDate = $this->endDate;
        }

        $this->initDates();
    }
};
?>

<div class="w-full relative mb-6 px-4 flex justify-center gap-4 items-center">
    <label class="font-semibold">{{ __('from') }}:</label>
    <div class="flex-1">
        <x-input type="date" wire:model.live="startDate" />
    </div>
    <span class="mx-2 font-semibold">{{ __('to') }}</span>
    <div class="flex-1">
        <x-input type="date" wire:model.live="endDate" />
    </div>
</div>
