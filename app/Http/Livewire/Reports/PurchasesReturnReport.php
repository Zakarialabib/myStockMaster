<?php

declare(strict_types=1);

namespace App\Http\Livewire\Reports;

use App\Models\PurchaseReturn;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class PurchasesReturnReport extends Component
{
    use WithPagination;

    public $suppliers;

    public $start_date;

    public $end_date;

    public $supplier_id;

    public $purchase_return_status;

    public $payment_status;

    protected $rules = [
        'start_date' => 'required|date|before:end_date',
        'end_date'   => 'required|date|after:start_date',
    ];

    public function mount()
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

    public function generateReport()
    {
        $this->validate();
        $this->render();
    }
}
