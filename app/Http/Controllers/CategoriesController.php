<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class CategoriesController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('access_product_categories'), 403);

        return view('admin.categories.index');
    }
}
