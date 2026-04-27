<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Customer;
use App\Models\Sale;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CustomersReport extends Component
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
    public ?string $customer_id = null;

    #[Url(history: true)]
    public ?string $payment_status = null;

    public function mount(): void
    {
        $this->start_date = $this->start_date ?? today()->subDays(30)->format('Y-m-d');
        $this->end_date = $this->end_date ?? today()->format('Y-m-d');
        $this->customer_id = $this->customer_id ?? '';
        $this->payment_status = $this->payment_status ?? '';
    }

    #[Computed]
    public function customers()
    {
        return Customer::query()->select(['id', 'name'])->get();
    }

    protected function baseQuery()
    {
        return Sale::query()
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, fn ($q) => $q->where('customer_id', $this->customer_id))
            ->when($this->payment_status, fn ($q) => $q->where('payment_status', $this->payment_status));
    }

    #[Computed]
    public function ltv()
    {
        return $this->baseQuery()->where('status', \App\Enums\SaleStatus::COMPLETED)->sum('total_amount');
    }

    #[Computed]
    public function totalDueAmount()
    {
        return $this->baseQuery()->sum('due_amount');
    }

    #[Computed]
    public function sales()
    {
        return $this->baseQuery()->with('customer')->orderBy('date', 'desc')->paginate(10);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.customers-report');
    }

    public function generateReport(): void
    {
        $this->validate();
    }
}
