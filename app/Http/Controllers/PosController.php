<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SalePayment;

class PosController extends Controller
{

    public function index() {
        Cart::instance('sale')->destroy();

        $customers = Customer::all();
        $product_categories = Category::all();

        return view('admin.sale.pos.index', compact('product_categories', 'customers'));
    }



}
