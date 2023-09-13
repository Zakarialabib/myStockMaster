<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class CustomerGroupController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('customer_group_access'), 403);

        return view('admin.customer-group.index');
    }
}
