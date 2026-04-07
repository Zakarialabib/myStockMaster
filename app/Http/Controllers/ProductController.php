<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return to_route('products.index');
    }
}
