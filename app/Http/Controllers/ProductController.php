<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __invoke()
    {
        return view('admin.products.index');
    }
}
