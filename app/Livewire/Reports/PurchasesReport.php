<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\WithAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]

class PurchasesReport extends Component
{
    use WithAlert;
    use WithPagination;

    public mixed $suppliers;

    #[Validate('required', message: 'The start date field is required.')]
    #[Validate('date', message: 'The start date field must be a valid date.')]
    #[Validate('before:end_date', message: 'The start date field must be before the end date field.')]
    public mixed $start_date;

    #[Validate('required', message: 'The end date field is required.')]
    #[Validate('date', message: 'The end date field must be a valid date.')]
    #[Validate('after:start_date', message: 'The end date field must be after the start date field.')]
    public mixed $end_date;

    public mixed $supplier_id;

    public mixed $purchase_status;

    public mixed $payment_status;

    public function mount(): void
    {
        $this->suppliers = Supplier::query()->select(['id', 'name'])->get();
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->supplier_id = '';
        $this->purchase_status = '';
        $this->payment_status = '';
    }

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $lengthAwarePaginator = Purchase::query()->whereDate('date')
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->supplier_id, fn ($query) => $query->where('supplier_id', $this->supplier_id))
            ->when($this->purchase_status, fn ($query) => $query->where('status', $this->purchase_status))
            ->when($this->payment_status, fn ($query) => $query->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')->paginate(10);

        return view('livewire.reports.purchases-report', [
            'purchases' => $lengthAwarePaginator,
        ]);
    }

    public function generateReport(): void
    {
        $this->validate();
        $this->render();
    }
}
