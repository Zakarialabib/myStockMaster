<?php

declare(strict_types=1);

namespace App\Http\Livewire\Reports;

use App\Models\PurchasePayment;
use App\Models\PurchaseReturnPayment;
use App\Models\SalePayment;
use App\Models\SaleReturnPayment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentsReport extends Component
{
    use WithPagination;

    public $start_date;

    public $end_date;

    public $payments;

    public $payment_method;

    protected $rules = [
        'start_date' => 'required|date|before:end_date',
        'end_date'   => 'required|date|after:start_date',
        'payments'   => 'required|string',
    ];

    protected $query;

    public function mount(): void
    {
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->payments = '';
        $this->query = null;
    }

    public function render()
    {
        $this->getQuery();

        return view('livewire.reports.payments-report', [
            'information' => $this->query ? $this->query->orderBy('date', 'desc')
                ->when($this->start_date, function ($query) {
                    return $query->whereDate('date', '>=', $this->start_date);
                })
                ->when($this->end_date, function ($query) {
                    return $query->whereDate('date', '<=', $this->end_date);
                })
                ->when($this->payment_method, function ($query) {
                    return $query->where('payment_method', $this->payment_method);
                })
                ->paginate(10) : collect(),
        ]);
    }

    public function generateReport()
    {
        $this->validate();
        $this->render();
    }

    public function updatedPayments($value)
    {
        $this->resetPage();
    }

    public function getQuery()
    {
        if ($this->payments === 'sale') {
            $this->query = SalePayment::query()->with('sale');
        } elseif ($this->payments === 'sale_return') {
            $this->query = SaleReturnPayment::query()->with('saleReturn');
        } elseif ($this->payments === 'purchase') {
            $this->query = PurchasePayment::query()->with('purchase');
        } elseif ($this->payments === 'purchase_return') {
            $this->query = PurchaseReturnPayment::query()->with('purchaseReturn');
        } else {
            $this->query = null;
        }
    }
}
