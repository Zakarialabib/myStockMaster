<?php

namespace App\Http\Livewire\Stats;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Transactions extends Component
{
    public $typeChart='yearly';
    
    public $officerCount,$categoriesCount,$topProduct,$productCount,$salesCount,$profit,$purchase,$purchaseCount,$lastPurchases,$supplierCount,$customerCount,$profitCount,$bestSales;
    public $lastSales;
    public $totalSales;
    public $totalPurchases;
    public $charts;
    public $purchases;
    public $purchases_count;
    public $sales;
    public $sales_count;

    public function mount()
    {
        $this->officerCount= User::count('id');
        $this->purchaseCount= Purchase::count('uuid');
        $this->categoriesCount= Category::count('id');
        $this->productCount= Product::count('id');
        $this->salesCount= Sale::count('id');
        $this->supplierCount= Supplier::count('id');
        $this->customerCount= Customer::count('id');
        $this->lastSales= Sale::select(['id','reference','total_amount','status','customer_id','user_id','date'])->with(['customer'])->latest()->take(5)->get();
        $this->lastPurchases= Purchase::select(['id','reference','total_amount','status','supplier_id','date'])->with(['supplier'])->latest()->take(5)->get();
        $this->bestSales= DB::select("SELECT COUNT(sales.id) as totalSales,SUM(sales.total_amount) as TotalAmount, users.name as name FROM `sales` INNER JOIN users ON sales.user_id=users.id WHERE MONTH(CURDATE())=MONTH(sales.created_at) GROUP by sales.user_id ORDER BY TotalAmount DESC LIMIT 5");
        $this->totalSales= Sale::sum('total_amount');
        $this->totalPurchases = Purchase::sum('total_amount');
        $this->topProduct= DB::select("SELECT SUM(sale_details.quantity) as qtyItem,products.name as name,products.code as code FROM `sale_details` INNER JOIN products ON products.id=sale_details.product_id WHERE MONTH(CURDATE())=MONTH(sale_details.created_at) GROUP BY sale_details.product_id ORDER by qtyItem DESC LIMIT 5");
      
        // $this->purchases =  DB::table('purchases')
        // ->whereDate('date', '>=', now()->subWeek())
        // ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as purchases'))
        // ->groupBy('date')
        // ->pluck('date');

        $this->purchases_count =  DB::table('purchases')
        ->whereDate('date', '>=', now()->subWeek())
        ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as purchases'))
        ->groupBy('date')
        ->pluck('purchases');

        // $this->sales =  DB::table('sales')
        // ->whereDate('date', '>=', now()->subWeek())
        // ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as sales'))
        // ->groupBy('date')
        // ->pluck('date');

        $this->sales_count =  DB::table('sales')
            ->whereDate('date', '>=', now()->subWeek())
            ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as sales'))
            ->groupBy('date')
            ->pluck('sales');
    
        $this->chart();
    }
    
    public function chart()
    {
        if ($this->typeChart=='monthly') {
            $sales= Sale::select(DB::raw('SUM(total_amount) as total,MONTH(date) as labels'), DB::raw('count(*) as sales'))->where(DB::raw('YEAR(date)'),'=',DB::raw('YEAR(CURDATE())'))->groupBy(DB::raw('MONTH(date)'))->get()->toArray();
            $purchases= Purchase::select(DB::raw('SUM(total_amount) as total,MONTH(date) as labels'),DB::raw('count(*) as purchases'))->where(DB::raw('YEAR(date)'),'=',DB::raw('YEAR(CURDATE())'))->groupBy(DB::raw('MONTH(date)'))->get()->toArray();
        }else{
            $sales= Sale::select(DB::raw('SUM(total_amount) as total,year(date) as labels'), DB::raw('count(*) as sales'))->groupBy(DB::raw('YEAR(date)'))->get()->toArray();
            $purchases= Purchase::select(DB::raw('SUM(total_amount) as total,year(date) as labels'),DB::raw('count(*) as purchases'))->groupBy(DB::raw('YEAR(date)'))->get()->toArray();
        }
        $this->charts = $this->getChart($sales,$purchases);
    }

    protected function getChart($sales,$purchases)
    {
        $dataarray=[];
        $i=0;
        foreach ($sales as $sale) {
            $dataarray['total']['sales'][$i]=$sale['total'];
            $dataarray['labels'][$i]=$sale['labels'];
            $i++;
        }
        $i=0;
        foreach ($purchases as $purchase) {
            $dataarray['total']['purchase'][$i]=$purchase['total'];
            $i++;
        }
        return json_encode($dataarray);
    }

  
    public function render()
    {
        return view('livewire.stats.transactions');
    }
}
