<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnPayment;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use App\Models\SaleReturnPayment;
use App\Models\Warehouse;
use App\Traits\WithAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProfitLossReport extends Component
{
    use WithAlert;

    #[Validate('required|date|before:end_date')]
    public ?string $start_date = null;

    #[Validate('required|date|after:start_date')]
    public ?string $end_date = null;

    public int $total_sales = 0;

    public float|int $sales_amount = 0;

    public int $total_purchases = 0;

    public float|int $purchases_amount = 0;

    public int $total_sale_returns = 0;

    public float|int $sale_returns_amount = 0;

    public int $total_purchase_returns = 0;

    public float|int $purchase_returns_amount = 0;

    public float|int $expenses_amount = 0;

    public float|int $profit_amount = 0;

    public float|int $payments_received_amount = 0;

    public float|int $payments_sent_amount = 0;

    public float|int $payments_net_amount = 0;

    public ?int $warehouse_id = null;

    public int $completed_purchases = 0;

    public int $pending_purchases = 0;

    public array $top_selling_products = [];

    public function mount(): void
    {
        $this->setDefaultDates();
    }

    #[Computed()]
    public function warehouses()
    {
        return Warehouse::query()->pluck('name', 'id')->toArray();
    }

    public function filterByDate(mixed $type): void
    {
        $this->setDefaultDates();

        switch ($type) {
            case 'day':
                $this->setDateRange(today(), now()->endOfDay());

                break;
            case 'month':
                $this->setDateRange(now()->startOfMonth(), now()->endOfMonth());

                break;
            case 'year':
                $this->setDateRange(now()->startOfYear(), now()->endOfYear());

                break;
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $this->setValues();

        return view('livewire.reports.profit-loss-report');
    }

    public function generateReport(): void
    {
        $this->validate();
    }

    public function setValues(): void
    {
        $this->total_sales = Sale::query()->completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->count();

        $this->sales_amount = Sale::query()->completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('total_amount') / 100;

        $this->total_purchases = Purchase::query()->completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->count();

        $this->purchases_amount = Purchase::query()->completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('total_amount') / 100;

        $this->total_sale_returns = SaleReturn::query()->completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->count();

        $this->sale_returns_amount = SaleReturn::query()->completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('total_amount') / 100;

        $this->total_purchase_returns = PurchaseReturn::query()->completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->count();

        $this->purchase_returns_amount = PurchaseReturn::query()->completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('total_amount') / 100;

        $this->expenses_amount = Expense::query()->when($this->start_date, fn ($query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn ($query) => $query->whereDate('date', '<=', $this->end_date))
            ->sum('amount') / 100;

        $this->completed_purchases = Purchase::query()->completed()
            ->when($this->start_date, fn ($query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn ($query) => $query->whereDate('date', '<=', $this->end_date))
            ->count();

        $this->pending_purchases = Purchase::query()->pending()
            ->when($this->start_date, fn ($query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn ($query) => $query->whereDate('date', '<=', $this->end_date))
            ->count();

        $this->profit_amount = $this->calculateProfit();
        $this->payments_received_amount = $this->calculatePaymentsReceived();
        $this->payments_sent_amount = $this->calculatePaymentsSent();
        $this->payments_net_amount = $this->payments_received_amount - $this->payments_sent_amount;
    }

    public function calculateProfit(): float
    {
        $revenue = $this->sales_amount - $this->sale_returns_amount;

        // Calculate product costs in a single query by joining product_warehouse
        $productCosts = \App\Models\SaleDetails::query()
            ->whereHas('sale', function ($query): void {
                $query->completed()
                    ->whereBetween('date', [$this->start_date, $this->end_date]);
            })
            ->join('product_warehouse', function ($join): void {
                $join->on('sale_details.product_id', '=', 'product_warehouse.product_id')
                    ->on('sale_details.warehouse_id', '=', 'product_warehouse.warehouse_id');
            })
            ->sum(\Illuminate\Support\Facades\DB::raw('product_warehouse.cost * sale_details.quantity')) / 100;

        return $revenue - $productCosts;
    }

    public function calculatePaymentsReceived(): float
    {
        $sale_payments = SalePayment::query()->when($this->start_date, fn ($query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn ($query) => $query->whereDate('date', '<=', $this->end_date))
            ->sum('amount') / 100;

        $purchase_return_payments = PurchaseReturnPayment::query()->when($this->start_date, fn ($query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn ($query) => $query->whereDate('date', '<=', $this->end_date))
            ->sum('amount') / 100;

        return $sale_payments + $purchase_return_payments;
    }

    public function calculatePaymentsSent(): float
    {
        $purchase_payments = PurchasePayment::query()->when($this->start_date, fn ($query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn ($query) => $query->whereDate('date', '<=', $this->end_date))
            ->sum('amount') / 100;

        $sale_return_payments = SaleReturnPayment::query()->when($this->start_date, fn ($query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn ($query) => $query->whereDate('date', '<=', $this->end_date))
            ->sum('amount') / 100;

        return $purchase_payments + $sale_return_payments + $this->expenses_amount;
    }

    private function setDefaultDates(): void
    {
        $this->start_date = now()->startOfYear()->format('Y-m-d');
        $this->end_date = now()->endOfDay()->format('Y-m-d');
    }

    private function setDateRange(mixed $startDate, mixed $endDate): void
    {
        $this->start_date = $startDate->format('Y-m-d');
        $this->end_date = $endDate->format('Y-m-d');
    }
}
