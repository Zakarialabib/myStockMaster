<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\PurchasePayment;
use App\Models\PurchaseReturnPayment;
use App\Models\SalePayment;
use App\Models\SaleReturnPayment;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PaymentsReport extends Component
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
    #[Validate('required|string')]
    public ?string $payments = null;

    #[Url(history: true)]
    public ?string $payment_method = null;

    public function mount(): void
    {
        $this->start_date = $this->start_date ?? today()->subDays(30)->format('Y-m-d');
        $this->end_date = $this->end_date ?? today()->format('Y-m-d');
        $this->payments = $this->payments ?? '';
        $this->payment_method = $this->payment_method ?? '';
    }

    #[Computed]
    public function cashFlowSummary()
    {
        if (! $this->payments) {
            return collect();
        }

        $query = match ($this->payments) {
            'sale' => SalePayment::query(),
            'sale_return' => SaleReturnPayment::query(),
            'purchase' => PurchasePayment::query(),
            'purchase_return' => PurchaseReturnPayment::query(),
            default => null,
        };

        if ($query) {
            return $query->whereDate('date', '>=', $this->start_date)
                ->whereDate('date', '<=', $this->end_date)
                ->selectRaw('payment_method, SUM(amount) as total_amount')
                ->groupBy('payment_method')
                ->get();
        }

        return collect();
    }

    #[Computed]
    public function information()
    {
        $query = match ($this->payments) {
            'sale' => SalePayment::query()->with(['sale', 'user', 'cashRegister.warehouse']),
            'sale_return' => SaleReturnPayment::query()->with(['saleReturn', 'user', 'cashRegister.warehouse']),
            'purchase' => PurchasePayment::query()->with(['purchase', 'user', 'cashRegister.warehouse']),
            'purchase_return' => PurchaseReturnPayment::query()->with(['purchaseReturn', 'user', 'cashRegister.warehouse']),
            default => null,
        };

        if ($query) {
            return $query->whereDate('date', '>=', $this->start_date)
                ->whereDate('date', '<=', $this->end_date)
                ->orderBy('date', 'desc')
                ->when($this->payment_method, fn ($q) => $q->where('payment_method', $this->payment_method))
                ->paginate(10);
        }

        return collect();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('report_access'), 403);

        return view('livewire.reports.payments-report');
    }

    public function generateReport(): void
    {
        $this->validate();
    }

    public function updatedPayments(mixed $value): void
    {
        $this->resetPage();
    }
}
