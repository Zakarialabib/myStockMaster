<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PurchasesReport extends Component
{
    use WithAlert;
    use WithPagination;

    #[Url(history: true)]
    #[Validate('required', message: 'The start date field is required.')]
    #[Validate('date', message: 'The start date field must be a valid date.')]
    #[Validate('before:end_date', message: 'The start date field must be before the end date field.')]
    public ?string $start_date = null;

    #[Url(history: true)]
    #[Validate('required', message: 'The end date field is required.')]
    #[Validate('date', message: 'The end date field must be a valid date.')]
    #[Validate('after:start_date', message: 'The end date field must be after the start date field.')]
    public ?string $end_date = null;

    #[Url(history: true)]
    public ?string $supplier_id = null;

    #[Url(history: true)]
    public ?string $purchase_status = null;

    #[Url(history: true)]
    public ?string $payment_status = null;

    public function mount(): void
    {
        $this->start_date = $this->start_date ?? today()->subDays(30)->format('Y-m-d');
        $this->end_date = $this->end_date ?? today()->format('Y-m-d');
        $this->supplier_id = $this->supplier_id ?? '';
        $this->purchase_status = $this->purchase_status ?? '';
        $this->payment_status = $this->payment_status ?? '';
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->select(['id', 'name'])->get();
    }

    #[Computed]
    public function purchases()
    {
        return Purchase::query()
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->supplier_id, fn ($query) => $query->where('supplier_id', $this->supplier_id))
            ->when($this->purchase_status, fn ($query) => $query->where('status', $this->purchase_status))
            ->when($this->payment_status, fn ($query) => $query->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')
            ->paginate(10);
    }

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.purchases-report');
    }

    public function generateReport(): void
    {
        $this->validate();
    }
}
