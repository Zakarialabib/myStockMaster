<?php

declare(strict_types=1);

namespace App\Http\Livewire\Reports;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnPayment;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use App\Models\SaleReturnPayment;
use App\Models\Warehouse;
use Livewire\Component;

class ProfitLossReport extends Component
{
    public $start_date = '';

    public $end_date = '';

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

    protected $rules = [
        'start_date' => 'required|date|before:end_date',
        'end_date'   => 'required|date|after:start_date',
    ];

    public function mount(): void
    {
        $this->startDate = now()->startOfYear()->format('Y-m-d');
        $this->endDate = now()->endOfDay()->format('Y-m-d');
    }

    public function filterDate($type)
    {
        switch ($type) {
            case 'day':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');

                break;
            case 'month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');

                break;
            case 'year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');

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
            ->when($this->start_date, function ($query) {
                return $query->whereDate('date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->count();

        $this->sales_amount = Sale::completed()
            ->when($this->start_date, function ($query) {
                return $query->whereDate('date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('total_amount') / 100;

        $this->total_purchases = Purchase::completed()
            ->when($this->start_date, function ($query) {
                return $query->whereDate('date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->count();

        $this->purchases_amount = Purchase::completed()
            ->when($this->start_date, function ($query) {
                return $query->whereDate('date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('total_amount') / 100;

        $this->total_sale_returns = SaleReturn::completed()
            ->when($this->start_date, function ($query) {
                return $query->whereDate('date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->count();

        $this->sale_returns_amount = SaleReturn::completed()
            ->when($this->start_date, function ($query) {
                return $query->whereDate('date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('total_amount') / 100;

        $this->total_purchase_returns = PurchaseReturn::completed()
            ->when($this->start_date, function ($query) {
                return $query->whereDate('date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->count();

        $this->purchase_returns_amount = PurchaseReturn::completed()
            ->when($this->start_date, function ($query) {
                return $query->whereDate('date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('total_amount') / 100;

        $this->expenses_amount = Expense::when($this->start_date, function ($query) {
            return $query->whereDate('date', '>=', $this->start_date);
        })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('date', '<=', $this->end_date);
            })
            ->sum('amount') / 100;

        $this->profit_amount = $this->calculateProfit();

        $this->payments_received_amount = $this->calculatePaymentsReceived();

        $this->payments_sent_amount = $this->calculatePaymentsSent();

        $this->payments_net_amount = $this->payments_received_amount - $this->payments_sent_amount;
    }

    public function getWarehousesProperty()
    {
        return Warehouse::pluck('name', 'id')->toArray();
    }

    public function calculateProfit()
    {
        $revenue = $this->sales_amount - $this->sale_returns_amount;

        $sales = Sale::completed()
            ->when($this->start_date, fn ($query) => $query->whereDate('date', '>=', $this->start_date))
            ->when($this->end_date, fn ($query) => $query->whereDate('date', '<=', $this->end_date))
            ->with('saleDetails')->get();

        $productCosts = 0;

         foreach ($sales as $sale) {
            foreach ($sale->saleDetails as $saleDetail) {
                // Assuming you have a warehouses relationship defined on the Product model
                $productWarehouse = $saleDetail->product->warehouses->where('warehouse_id', $this->warehouse_id)->first();

                if ($productWarehouse) {
                    $productCosts += $productWarehouse->cost * $saleDetail->quantity;
                }
            }
        }

        return $revenue - $productCosts;
    }


    public function calculatePaymentsReceived()
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

    public function calculatePaymentsSent()
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
}
