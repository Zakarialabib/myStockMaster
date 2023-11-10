<?php

declare(strict_types=1);

namespace App\Http\Livewire\Reports;

use App\Models\Expense;
use App\Models\ProductWarehouse;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnPayment;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use App\Models\SaleReturnPayment;
use App\Models\Warehouse;
use Livewire\Component;

class ProfitLossReport extends Component
{
    public $start_date;
    public $end_date;
    public $total_sales = 0;
    public $sales_amount = 0;
    public $total_purchases = 0;
    public $purchases_amount = 0;
    public $total_sale_returns = 0;
    public $sale_returns_amount = 0;
    public $total_purchase_returns = 0;
    public $purchase_returns_amount = 0;
    public $expenses_amount = 0;
    public $profit_amount = 0;
    public $payments_received_amount = 0;
    public $payments_sent_amount = 0;
    public $payments_net_amount = 0;
    public $warehouse_id;
    public $warehouses;
    public $completed_purchases;
    public $pending_purchases;
    public $top_selling_products;

    protected $rules = [
        'start_date' => 'required|date|before:end_date',
        'end_date'   => 'required|date|after:start_date',
    ];

    public function mount(): void
    {
        $this->setDefaultDates();
        $this->warehouses = Warehouse::pluck('name', 'id')->toArray();
    }

    public function filterByDate($type)
    {
        $this->setDefaultDates();

        switch ($type) {
            case 'day':
                $this->setDateRange(now()->startOfDay(), now()->endOfDay());
                break;
            case 'month':
                $this->setDateRange(now()->startOfMonth(), now()->endOfMonth());
                break;
            case 'year':
                $this->setDateRange(now()->startOfYear(), now()->endOfYear());
                break;
        }
    }

    public function render()
    {
        $this->setValues();

        return view('livewire.reports.profit-loss-report');
    }

    public function generateReport()
    {
        $this->validate();
    }

    public function setValues()
    {
        $this->total_sales = Sale::completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->count();

        $this->sales_amount = Sale::completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('total_amount') / 100;

        $this->total_purchases = Purchase::completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->count();

        $this->purchases_amount = Purchase::completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('total_amount') / 100;

        $this->total_sale_returns = SaleReturn::completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->count();

        $this->sale_returns_amount = SaleReturn::completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('total_amount') / 100;

        $this->total_purchase_returns = PurchaseReturn::completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->count();

        $this->purchase_returns_amount = PurchaseReturn::completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('total_amount') / 100;

        $this->expenses_amount = Expense::when($this->start_date, function ($query) {
            return $query->whereDate('date', '>=', $this->start_date);
        })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('amount') / 100;

        $this->completed_purchases = Purchase::completed()
            ->when($this->start_date, fn ($query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn ($query) => $query->whereDate('date', '<=', $this->end_date))
            ->count();

        $this->pending_purchases = Purchase::pending()
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

        $sales = Sale::completed()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->with('saleDetails.product') // Ensure you load related models
            ->get();

        $productCosts = 0;

        foreach ($sales as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                $productCosts += $this->getProductCost($saleDetail->product_id, $saleDetail->warehouse_id) * $saleDetail->quantity;
            }
        }

        return $revenue - $productCosts;
    }

    private function getProductCost($productId, $warehouseId): float
    {
        // Retrieve the product cost from the ProductWarehouse pivot table
        $productWarehouse = ProductWarehouse::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        return $productWarehouse ? $productWarehouse->cost : 0;
    }

    public function calculatePaymentsReceived(): float
    {
        $sale_payments = SalePayment::when($this->start_date, function ($query) {
            return $query->whereDate('date', '>=', $this->start_date);
        })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('amount') / 100;

        $purchase_return_payments = PurchaseReturnPayment::when($this->start_date, function ($query) {
            return $query->whereDate('date', '>=', $this->start_date);
        })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('amount') / 100;

        return $sale_payments + $purchase_return_payments;
    }

    public function calculatePaymentsSent(): float
    {
        $purchase_payments = PurchasePayment::when($this->start_date, function ($query) {
            return $query->whereDate('date', '>=', $this->start_date);
        })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('amount') / 100;

        $sale_return_payments = SaleReturnPayment::when($this->start_date, function ($query) {
            return $query->whereDate('date', '>=', $this->start_date);
        })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('amount') / 100;

        return $purchase_payments + $sale_return_payments + $this->expenses_amount;
    }

    private function setDefaultDates()
    {
        $this->start_date = now()->startOfYear()->format('Y-m-d');
        $this->end_date = now()->endOfDay()->format('Y-m-d');
    }

    private function setDateRange($startDate, $endDate)
    {
        $this->start_date = $startDate->format('Y-m-d');
        $this->end_date = $endDate->format('Y-m-d');
    }
}
