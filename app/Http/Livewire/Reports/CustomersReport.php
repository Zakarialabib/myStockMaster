<?php

declare(strict_types=1);

namespace App\Http\Livewire\Reports;

use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\SaleReturn;
use Livewire\Component;
use Livewire\WithPagination;

class CustomersReport extends Component
{
    use WithPagination;

    public $customer_id;
    public $customers;
    public $start_date;
    public $end_date;
    public $payment_status;
    public $sales;
    public $saleReturns;
    public $purchase_status;
    public $quotations;

    protected $rules = [
        'start_date' => 'required|date|before:end_date',
        'end_date'   => 'required|date|after:start_date',
    ];

    public function mount()
    {
        $this->customers = Customer::select(['id', 'name'])->get();
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->customer_id = '';
        $this->purchase_status = '';
        $this->payment_status = '';
    }

    public function getSalesProperty()
    {
        return Sale::whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, function ($query) {
                return $query->where('customer_id', $this->customer_id);
            })
            ->when($this->payment_status, function ($query) {
                return $query->where('payment_status', $this->payment_status);
            })
            ->orderBy('date', 'desc')->paginate(10);
    }

    public function getSaleReturnsProperty()
    {
        return SaleReturn::whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, function ($query) {
                return $query->where('customer_id', $this->customer_id);
            })
            ->when($this->payment_status, function ($query) {
                return $query->where('payment_status', $this->payment_status);
            })
            ->orderBy('date', 'desc')->paginate(10);
    }

    public function getQuotationProperty()
    {
        return Quotation::whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, function ($query) {
                return $query->where('customer_id', $this->customer_id);
            })
            ->when($this->payment_status, function ($query) {
                return $query->where('payment_status', $this->payment_status);
            })
            ->orderBy('date', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.reports.customers-report', [

        ]);
    }

    public function generateReport()
    {
        $this->validate();
        $this->render();
    }
}
