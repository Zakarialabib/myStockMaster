<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\SaleReturn;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class CustomersReport extends Component
{
    use WithPagination;

    public $customer_id;

    public $customers;

    #[Validate('required', message: 'The start date field is required.')]
    #[Validate('date', message: 'The start date field must be a valid date.')]
    #[Validate('before:end_date', message: 'The start date field must be before the end date field.')]
    public $start_date;

    #[Validate('required', message: 'The end date field is required.')]
    #[Validate('date', message: 'The end date field must be a valid date.')]
    #[Validate('after:start_date', message: 'The end date field must be after the start date field.')]
    public $end_date;

    public $payment_status;

    public $purchase_status;

    public function mount(): void
    {
        $this->customers = Customer::select(['id', 'name'])->get();
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->customer_id = '';
        $this->purchase_status = '';
        $this->payment_status = '';
    }

    #[Computed]
    public function sales()
    {
        return Sale::whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, fn ($query) => $query->where('customer_id', $this->customer_id))
            ->when($this->payment_status, fn ($query) => $query->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')->paginate(10);
    }

    #[Computed]
    public function saleReturns()
    {
        return SaleReturn::whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, fn ($query) => $query->where('customer_id', $this->customer_id))
            ->when($this->payment_status, fn ($query) => $query->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')->paginate(10);
    }

    #[Computed]
    public function quotations()
    {
        return Quotation::whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, fn ($query) => $query->where('customer_id', $this->customer_id))
            ->when($this->payment_status, fn ($query) => $query->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.reports.customers-report');
    }

    public function generateReport(): void
    {
        $this->validate();
        $this->render();
    }
}
