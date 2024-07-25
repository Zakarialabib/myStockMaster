<?php

declare(strict_types=1);

namespace App\Livewire\Stats;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturnPayment;
use App\Models\SaleDetails;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleReturnPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Transactions extends Component
{
    public $typeChart = 'monthly';

    public $profit;

    public $purchase;

    public $lastPurchases;

    public $bestSales;

    public $lastSales;

    public $charts;

    public $purchases;

    public $purchases_count;

    public $sales;

    public $sales_count;

    public $startDate;

    public $endDate;

    public $purchasesCount;

    public $salesTotal;

    public $stockValue;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();

        $this->lastSales = Sale::with('customer')
            ->latest()
            ->take(5)
            ->get(['id', 'reference', 'total_amount', 'status', 'customer_id', 'user_id', 'date']);

        $this->lastPurchases = Purchase::with('supplier')
            ->latest()
            ->take(5)
            ->get(['id', 'reference', 'total_amount', 'status', 'supplier_id', 'date', 'user_id']);

        $this->bestSales = Sale::query()
            ->select('sales.*', 'customers.name')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->with('user', 'customer')
            ->whereMonth('sales.created_at', Carbon::now()->startOfMonth())
            ->orderBy('sales.total_amount', 'desc')
            ->take(5)
            ->get();

        $this->purchases_count = Purchase::where('date', '>=', Carbon::now()->subWeek())
            ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as purchases'))
            ->groupBy('date')
            ->pluck('purchases');

        $this->sales_count = Sale::whereDate('date', '>=', Carbon::now()->subWeek())
            ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as sales'))
            ->groupBy('date')
            ->pluck('sales');

        $this->chart();

        // $this->best_selling_product = $this->calculateBestSellingProduct();
        // $this->number_of_products_sold = $this->calculateNumberOfProductsSold();
        // $this->average_purchase_return_amount = $this->calculateAveragePurchaseReturnAmount();
        // $this->common_return_reason = $this->findCommonReturnReason();
        // $this->average_payment_received_per_sale = $this->calculateAveragePaymentReceivedPerSale();
        // $this->significant_payment_changes = $this->detectSignificantPaymentChanges();
    }

    // protected function calculateBestSellingProduct()
    // {
    //     // Your logic to determine the best-selling product
    // }

    // protected function calculateNumberOfProductsSold()
    // {
    //     // Your logic to calculate the total number of products sold
    // }

    // protected function calculateAveragePurchaseReturnAmount()
    // {
    //     // Your logic to calculate the average amount per purchase return
    // }

    // protected function findCommonReturnReason()
    // {
    //     // Your logic to find the most common reason for purchase returns
    // }

    // protected function calculateAveragePaymentReceivedPerSale()
    // {
    //     // Your logic to calculate the average payment amount received per sale
    // }

    // protected function detectSignificantPaymentChanges()
    // {
    //     // Your logic to detect significant changes in payment patterns
    // }

    public function chart(): void
    {
        $query = Sale::selectRaw('SUM(total_amount) as total, SUM(due_amount) as due_amount')
            ->when($this->typeChart === 'monthly', static fn ($q) => $q->selectRaw('MONTH(date) as labels, COUNT(*) as sales')
                ->whereYear('date', '=', date('Y'))
                ->groupByRaw('MONTH(date)'), static fn ($q) => $q->selectRaw('YEAR(date) as labels, COUNT(*) as sales')
                ->groupByRaw('YEAR(date)'))
            ->get()
            ->toArray();

        $sales = [
            'total'      => array_column($query, 'total'),
            'due_amount' => array_map(static fn ($total, $dueAmount): int|float => $total - $dueAmount, array_column($query, 'total'), array_column($query, 'due_amount')),
            'labels'     => array_column($query, 'labels'),
        ];

        $query = Purchase::selectRaw('SUM(total_amount) as total, SUM(due_amount) as due_amount')
            ->when($this->typeChart === 'monthly', static fn ($q) => $q->selectRaw('MONTH(date) as labels, COUNT(*) as purchases')
                ->whereYear('date', '=', date('Y'))
                ->groupByRaw('MONTH(date)'), static fn ($q) => $q->selectRaw('YEAR(date) as labels, COUNT(*) as purchases')
                ->groupByRaw('YEAR(date)'))
            ->get()
            ->toArray();

        $purchases = [
            'total'      => array_column($query, 'total'),
            'due_amount' => array_map(static fn ($total, $dueAmount): int|float => $total - $dueAmount, array_column($query, 'total'), array_column($query, 'due_amount')),
            'labels'     => array_column($query, 'labels'),
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
        ], JSON_THROW_ON_ERROR);
    }

    protected function getChart($sales, $purchases): string
    {
        $dataarray = [];
        $i = 0;

        foreach ($sales as $sale) {
            $dataarray['total']['sales'][$i] = $sale['total'];
            $dataarray['due_amount']['sales'][$i] = $sale['total'] - $sale['due_amount'];
            $dataarray['labels'][$i] = $sale['labels'];
            ++$i;
        }

        $i = 0;

        foreach ($purchases as $purchase) {
            $dataarray['total']['purchase'][$i] = $purchase['total'];
            $dataarray['due_amount']['purchase'][$i] = $purchase['total'] - $purchase['due_amount'];
            ++$i;
        }

        return json_encode($dataarray, JSON_THROW_ON_ERROR);
    }

    #[Computed]
    public function topProducts()
    {
        return  SaleDetails::query()
            ->selectRaw('
                SUM(sale_details.quantity) as qtyItem,
                products.name as name,
                products.code as code,
                SUM(sale_details.sub_total) as totalSalesAmount,
                sale_details.id,
                sales.warehouse_id
            ')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->whereMonth('sale_details.created_at', Carbon::now()->startOfMonth())
            ->groupBy(['sale_details.id', 'sale_details.product_id', 'products.name', 'products.code', 'sales.warehouse_id'])
            ->orderByDesc('qtyItem')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function topCustomers()
    {
        return Sale::with(['saleDetails.sale.customer'])
            ->selectRaw('
                SUM(sales.total_amount) as totalSalesAmount,
                customers.name as name,
                customers.id as id,
                sales.id,
                sales.warehouse_id
            ')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->whereMonth('sales.created_at', Carbon::now()->startOfMonth())
            ->groupBy(['sales.id', 'sales.customer_id', 'customers.name', 'customers.id', 'sales.warehouse_id'])
            ->orderByDesc('totalSalesAmount')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function monthlyChartOptions(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $customerNameTotal = DB::table('sale_payments')
            ->join('sales', 'sale_payments.sale_id', '=', 'sales.id')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->whereBetween('sale_payments.created_at', [$startDate, $endDate])
            ->groupBy('customers.name')
            ->select(DB::raw('SUM(sale_payments.amount) / 100 as sale_total_payment'), 'customers.name as customer_name')
            ->get();

        $supplierNameTotal = DB::table('purchase_payments')
            ->join('purchases', 'purchase_payments.purchase_id', '=', 'purchases.id')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->whereBetween('purchase_payments.created_at', [$startDate, $endDate])
            ->groupBy('suppliers.name')
            ->select(DB::raw('SUM(purchase_payments.amount) / 100 as purchase_total_payment'), 'suppliers.name as supplier_name')->get();

        $customerSeries = $customerNameTotal->pluck('sale_total_payment')->toArray();
        $customerLabels = $customerNameTotal->pluck('customer_name')->toArray();

        $supplierSeries = $supplierNameTotal->pluck('purchase_total_payment')->toArray();
        $supplierLabels = $supplierNameTotal->pluck('supplier_name')->toArray();

        return [
            'chart' => [
                'type'  => 'bar',
                'width' => '100%',
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal'   => true,
                    'borderRadius' => 4,
                ],
            ],
            'series' => [
                [
                    'name' => __('Customer Payments'),
                    'data' => $customerSeries,
                ],
                [
                    'name' => __('Supplier Payments'),
                    'data' => $supplierSeries,
                ],
            ],
            'xaxis' => [
                'categories' => [
                    $customerLabels,
                    $supplierLabels,
                ], // Assuming customer names are used as labels
            ],
            'yaxis' => [
                'title' => [
                    'text' => __('Customers/Suppliers'),
                ],
            ],
            'legend' => [
                'position'        => 'top',
                'horizontalAlign' => 'center',
                'offsetX'         => 40,
            ],
        ];
    }

    #[Computed]
    public function dailyChartOptions(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();

        // Get all days in the current month
        $daysInMonth = [];
        $currentDay = Carbon::now()->startOfMonth();

        while ($currentDay->month === $currentMonth->month) {
            $daysInMonth[] = $currentDay->format('Y-m-d');
            $currentDay->addDay();
        }

        // Get sales data for each day in the current month
        $salesData = Sale::selectRaw('DATE(date) as day, SUM(total_amount) as total_sales')
            ->whereBetween('date', [$currentMonth, Carbon::now()->endOfMonth()])
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->get();

        // Get purchase data for each day in the current month
        $purchasesData = Purchase::selectRaw('DATE(date) as day, SUM(total_amount) as total_purchases')
            ->whereBetween('date', [$currentMonth, Carbon::now()->endOfMonth()])
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->get();

        // Combine sales and purchase data
        $chartData = [];

        foreach ($daysInMonth as $day) {
            $sale = $salesData->where('day', $day)->first();
            $purchase = $purchasesData->where('day', $day)->first();
            $chartData[] = [
                'day'       => $day,
                'sales'     => ($sale) ? $sale->total_sales : 0,
                'purchases' => ($purchase) ? $purchase->total_purchases : 0,
            ];
        }

        // Create stacked bar chart options
        $dailyChartOptions = [
            'chart' => [
                'type'    => 'bar',
                'stacked' => true,
                'width'   => '100%',
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal'  => false,
                    'endingShape' => 'flat',
                    'columnWidth' => '70%',
                ],
            ],
            'series' => [
                [
                    'name' => __('Sales'),
                    'data' => array_column($chartData, 'sales'),
                ],
                [
                    'name' => __('Purchases'),
                    'data' => array_column($chartData, 'purchases'),
                ],
            ],
            'xaxis' => [
                'categories' => array_column($chartData, 'day'),
                'labels'     => [
                    'rotateAlways' => true,
                    'rotate'       => -45,
                ],
            ],
            'yaxis' => [
                'title' => [
                    'text' => __('Amount'),
                ],
            ],
            'legend' => [
                'position'        => 'top',
                'horizontalAlign' => 'center',
                'offsetX'         => 40,
            ],
            'colors' => ['#4CAF50', '#F44336'],
        ];

        return $dailyChartOptions;
    }

    #[Computed]
    public function paymentChart(): array
    {
        $dates = collect();
        $dateLabels = [];
        $paymentSentData = [];
        $paymentReceivedData = [];

        foreach (range(-11, 0) as $i) {
            $date = Carbon::now()->addMonths($i);
            $dateLabels[] = $date->format('M Y');
            $dates->put($date->format('Y-m'), 0);
        }

        $date_range = Carbon::today()->subYear()->format('Y-m-d');

        $sale_payments = SalePayment::where('date', '>=', $date_range)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as amount")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $sale_return_payments = SaleReturnPayment::where('date', '>=', $date_range)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as amount")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $purchase_payments = PurchasePayment::where('date', '>=', $date_range)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as amount")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $purchase_return_payments = PurchaseReturnPayment::where('date', '>=', $date_range)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as amount")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $expenses = Expense::where('date', '>=', $date_range)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as amount")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Populate the data arrays
        foreach ($dateLabels as $label) {
            $month = Carbon::createFromFormat('M Y', $label);
            $monthKey = $month->format('Y-m');

            $paymentSentData[] = ($purchase_payments->where('month', $monthKey)->first()->amount ?? 0)
                + ($sale_return_payments->where('month', $monthKey)->first()->amount ?? 0)
                + ($expenses->where('month', $monthKey)->first()->amount ?? 0);

            $paymentReceivedData[] = ($sale_payments->where('month', $monthKey)->first()->amount ?? 0)
                + ($purchase_return_payments->where('month', $monthKey)->first()->amount ?? 0);

            $dates[$monthKey] = 1; // Mark the month as found
        }

        // Fill any missing months with zeros
        foreach ($dates as $month => $value) {
            if ($value === 0) {
                $dateLabels[] = Carbon::createFromFormat('Y-m', $month)->format('M Y');
                $paymentSentData[] = 0;
                $paymentReceivedData[] = 0;
            }
        }

        return [
            'chart' => [
                'type'  => 'bar',
                'width' => '100%',
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal'  => false,
                    'endingShape' => 'flat',
                    'columnWidth' => '70%',
                ],
            ],
            'series' => [
                [
                    'name' => __('Payment Sent'),
                    'data' => $paymentSentData,
                ],
                [
                    'name' => __('Payment Received'),
                    'data' => $paymentReceivedData,
                ],
            ],
            'xaxis' => [
                'categories' => $dateLabels,
                'labels'     => [
                    'rotateAlways' => true,
                    'rotate'       => -45,
                ],
            ],
            'yaxis' => [
                'title' => [
                    'text' => __('Amount'),
                ],
            ],
            'legend' => [
                'position'        => 'top',
                'horizontalAlign' => 'center',
                'offsetX'         => 40,
            ],
            'colors' => ['#F44336', '#4CAF50'],
        ];
    }

    public function render()
    {
        return view('livewire.stats.transactions');
    }
}
