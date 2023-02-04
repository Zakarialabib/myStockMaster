<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    public function __invoke()
    {
        return view('admin.products.index');
    }
}
