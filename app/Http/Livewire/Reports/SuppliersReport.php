<?php

declare(strict_types=1);

namespace App\Http\Livewire\Reports;

use App\Models\Purchase;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SuppliersReport extends Component
{
    use WithPagination;

    public $supplier_id;
    public $suppliers;
    public $start_date;
    public $end_date;
    public $payment_status;
    public $purchase_status;

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
        $this->purchase_status = '';
        $this->payment_status = '';
    }

    public function getPurchasesProperty()
    {
        return Purchase::whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->supplier_id, function ($query) {
                return $query->where('supplier_id', $this->supplier_id);
            })
            ->when($this->payment_status, function ($query) {
                return $query->where('payment_status', $this->payment_status);
            })
            ->orderBy('date', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.reports.suppliers-report', [
        ]);
    }

    public function generateReport()
    {
        $this->validate();
        $this->render();
    }
}
