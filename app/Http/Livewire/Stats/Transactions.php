<?php

declare(strict_types=1);

namespace App\Http\Livewire\Stats;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Transactions extends Component
{
    public $typeChart = 'monthly';

    public $categoriesCount;
    public $topProduct;
    public $productCount;
    public $salesCount;
    public $profit;
    public $purchase;
    public $purchaseCount;
    public $lastPurchases;
    public $supplierCount;
    public $customerCount;
    public $profitCount;
    public $bestSales;
    public $lastSales;
    public $charts;
    public $purchases;
    public $purchases_count;
    public $sales;
    public $sales_count;

    public function mount()
    {
        $this->categoriesCount = Category::count('id');
        $this->productCount = Product::count('id');
        $this->supplierCount = Supplier::count('id');
        $this->customerCount = Customer::count('id');
        $this->lastSales = Sale::with('customer')
            ->latest()
            ->take(5)
            ->get(['id', 'reference', 'total_amount', 'status', 'customer_id', 'user_id', 'date']);

        $this->lastPurchases = Purchase::with('supplier')
            ->latest()
            ->take(5)
            ->get(['id', 'reference', 'total_amount', 'status', 'supplier_id', 'date','user_id']);

        $this->bestSales = DB::table('sales')
            ->selectRaw('COUNT(sales.id) as totalSales, SUM(sales.total_amount) as TotalAmount, customers.name as name')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->whereRaw('MONTH(CURDATE()) = MONTH(sales.created_at)')
            ->groupBy('sales.customer_id', 'customers.name')
            ->orderByDesc('TotalAmount')
            ->limit(5)
            ->get();

        $this->topProduct = DB::table('sale_details')
            ->selectRaw('SUM(sale_details.quantity) as qtyItem, products.name as name, products.code as code')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->whereRaw('MONTH(CURDATE()) = MONTH(sale_details.created_at)')
            ->groupBy('sale_details.product_id', 'products.name', 'products.code')
            ->orderByDesc('qtyItem')
            ->limit(5)
            ->get();

        $this->purchases_count = Purchase::where('date', '>=', now()->subWeek())
            ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as purchases'))
            ->groupBy('date')
            ->pluck('purchases');

        $this->sales_count = Sale::whereDate('date', '>=', now()->subWeek())
            ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as sales'))
            ->groupBy('date')
            ->pluck('sales');

        $this->chart();
    }

    public function chart()
    {
        $query = Sale::selectRaw('SUM(total_amount) as total, SUM(due_amount) as due_amount')
            ->when($this->typeChart === 'monthly', function ($q) {
                return $q->selectRaw('MONTH(date) as labels, COUNT(*) as sales')
                    ->whereYear('date', '=', date('Y'))
                    ->groupByRaw('MONTH(date)');
            }, function ($q) {
                return $q->selectRaw('YEAR(date) as labels, COUNT(*) as sales')
                    ->groupByRaw('YEAR(date)');
            })
            ->get()
            ->toArray();

        $sales = [
            'total'      => array_column($query, 'total'),
            'due_amount' => array_map(function ($total, $dueAmount) {
                return $total - $dueAmount;
            }, array_column($query, 'total'), array_column($query, 'due_amount')),
            'labels' => array_column($query, 'labels'),
        ];

        $query = Purchase::selectRaw('SUM(total_amount) as total, SUM(due_amount) as due_amount')
            ->when($this->typeChart === 'monthly', function ($q) {
                return $q->selectRaw('MONTH(date) as labels, COUNT(*) as purchases')
                    ->whereYear('date', '=', date('Y'))
                    ->groupByRaw('MONTH(date)');
            }, function ($q) {
                return $q->selectRaw('YEAR(date) as labels, COUNT(*) as purchases')
                    ->groupByRaw('YEAR(date)');
            })
            ->get()
            ->toArray();

        $purchases = [
            'total'      => array_column($query, 'total'),
            'due_amount' => array_map(function ($total, $dueAmount) {
                return $total - $dueAmount;
            }, array_column($query, 'total'), array_column($query, 'due_amount')),
            'labels' => array_column($query, 'labels'),
        ];

        $this->charts = json_encode([
            'total' => [
                'sales'    => $sales['total'],
                'purchase' => $purchases['total'],
            ],
            'due_amount' => [
                'sales'    => $sales['due_amount'],
                'purchase' => $purchases['due_amount'],
            ],
            'labels' => $sales['labels'],
        ]);
    }

    protected function getChart($sales, $purchases)
    {
        $dataarray = [];
        $i = 0;

        foreach ($sales as $sale) {
            $dataarray['total']['sales'][$i] = $sale['total'];
            $dataarray['due_amount']['sales'][$i] = $sale['total'] - $sale['due_amount'];
            $dataarray['labels'][$i] = $sale['labels'];
            $i++;
        }

        $i = 0;

        foreach ($purchases as $purchase) {
            $dataarray['total']['purchase'][$i] = $purchase['total'];
            $dataarray['due_amount']['purchase'][$i] = $purchase['total'] - $purchase['due_amount'];
            $i++;
        }

        return json_encode($dataarray);
    }

    public function render()
    {
        return view('livewire.stats.transactions');
    }
}
