<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Customer;
use App\Models\SaleReturn;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]

class SalesReturnReport extends Component
{
    use WithAlert;
    use WithPagination;

    #[Validate('required', message: 'The start date field is required.')]
    #[Validate('date', message: 'The start date field must be a valid date.')]
    #[Validate('before:end_date', message: 'The start date field must be before the end date field.')]
    public string $start_date;

    #[Validate('required', message: 'The end date field is required.')]
    #[Validate('date', message: 'The end date field must be a valid date.')]
    #[Validate('after:start_date', message: 'The end date field must be after the start date field.')]
    public string $end_date;

    public ?string $customer_id = null;

    public ?string $sale_return_status = null;

    public ?string $payment_status = null;

    public function mount(): void
    {
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->customer_id = '';
        $this->sale_return_status = '';
        $this->payment_status = '';
    }

    #[Computed]
    public function customers()
    {
        return Customer::select(['id', 'name'])->get();
    }

    public function render()
    {
        abort_if(Gate::denies('report_access'), 403);

        $sale_returns = SaleReturn::whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, fn ($q) => $q->where('customer_id', $this->customer_id))
            ->when($this->sale_return_status, fn ($q) => $q->where('sale_return_status', $this->sale_return_status))
            ->when($this->payment_status, fn ($q) => $q->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')->paginate(10);

        return view('livewire.reports.sales-return-report', [
            'sale_returns' => $sale_returns,
        ]);
    }

    public function generateReport(): void
    {
        $this->validate();
        $this->render();
    }
}
