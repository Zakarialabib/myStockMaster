<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_access'), 403);

        return view('admin.products.index');
    }
}
