<?php

namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;

class ProductController extends Controller
{

    public function index() {
        abort_if(Gate::denies('access_products'), 403);

        return view('admin.products.index');
    }

}
