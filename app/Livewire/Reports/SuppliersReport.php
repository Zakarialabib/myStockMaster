<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SuppliersReport extends Component
{
    use WithAlert;
    use WithPagination;

    #[Url(history: true)]
    #[Validate('required')]
    #[Validate('date')]
    #[Validate('before:end_date')]
    public ?string $start_date = null;

    #[Url(history: true)]
    #[Validate('required')]
    #[Validate('date')]
    #[Validate('after:start_date')]
    public ?string $end_date = null;

    #[Url(history: true)]
    public ?string $supplier_id = null;

    #[Url(history: true)]
    public ?string $payment_status = null;

    public function mount(): void
    {
        $this->start_date = $this->start_date ?? today()->subDays(30)->format('Y-m-d');
        $this->end_date = $this->end_date ?? today()->format('Y-m-d');
        $this->supplier_id = $this->supplier_id ?? '';
        $this->payment_status = $this->payment_status ?? '';
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->select(['id', 'name'])->get();
    }

    protected function baseQuery()
    {
        return Purchase::query()
            ->whereDate('date', '>=', $this->start_date)
            ->whereDate('date', '<=', $this->end_date)
            ->when($this->supplier_id, fn ($q) => $q->where('supplier_id', $this->supplier_id))
            ->when($this->payment_status, fn ($q) => $q->where('payment_status', $this->payment_status));
    }

    #[Computed]
    public function totalPayables()
    {
        return $this->baseQuery()->sum('due_amount');
    }

    #[Computed]
    public function purchases()
    {
        return $this->baseQuery()->with('supplier')->orderBy('date', 'desc')->paginate(10);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.reports.suppliers-report');
    }

    public function generateReport(): void
    {
        $this->validate();
    }
}
