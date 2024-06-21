<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class PurchasesReturnReport extends Component
{
    use WithPagination;

    public $suppliers;

    public $supplier_id;

    public $purchase_return_status;

    public $payment_status;

    #[Validate('required', message: 'The start date field is required.')]
    #[Validate('date', message: 'The start date field must be a valid date.')]
    #[Validate('before:end_date', message: 'The start date field must be before the end date field.')]
    public $start_date;

    #[Validate('required', message: 'The end date field is required.')]
    #[Validate('date', message: 'The end date field must be a valid date.')]
    #[Validate('after:start_date', message: 'The end date field must be after the start date field.')]
    public $end_date;

    public function mount(): void
    {
        $this->suppliers = Supplier::select(['id', 'name'])->get();
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->supplier_id = '';
        $this->purchase_return_status = '';
        $this->payment_status = '';
    }

    public function render()
    {
        abort_if(Gate::denies('report_access'), 403);

        $purchase_returns = PurchaseReturn::whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->supplier_id, fn ($q) => $q->where('supplier_id', $this->supplier_id))
            ->when($this->purchase_return_status, fn ($q) => $q->where('purchase_return_status', $this->purchase_return_status))
            ->when($this->payment_status, fn ($q) => $q->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')->paginate(10);

        return view('livewire.reports.purchases-return-report', [
            'purchase_returns' => $purchase_returns,
        ]);
    }

    public function generateReport(): void
    {
        $this->validate();
        $this->render();
    }
}
