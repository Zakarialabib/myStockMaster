<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Customer;
use App\Models\Sale;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]

class SalesReport extends Component
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

    public ?string $sale_status = null;

    public ?string $payment_status = null;

    public function mount(): void
    {
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->customer_id = '';
        $this->sale_status = '';
        $this->payment_status = '';
    }

    #[Computed]
    public function customers()
    {
        return Customer::query()->select(['id', 'name'])->get();
    }

    public function placeholder(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $lengthAwarePaginator = Sale::with('customer')->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->customer_id, fn ($q) => $q->where('customer_id', $this->customer_id))
            ->when($this->sale_status, fn ($q) => $q->where('sale_status', $this->sale_status))
            ->when($this->payment_status, fn ($q) => $q->where('payment_status', $this->payment_status))
            ->orderBy('date', 'desc')->paginate(10);

        return view('livewire.reports.sales-report', [
            'sales' => $lengthAwarePaginator,
        ]);
    }

    public function generateReport(): void
    {
        $this->validate();
        $this->render();
    }
}
