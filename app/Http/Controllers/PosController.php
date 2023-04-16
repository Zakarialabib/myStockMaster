<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;

class PosController extends Controller
{
    public function index()
    {
        Cart::instance('sale')->destroy();

        $customers = Customer::select(['id', 'name'])->get();
        $product_categories = Category::select(['id', 'name'])->get();

        return view('admin.sale.pos.index', compact('product_categories', 'customers'));
    }
}
