<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class CustomersController extends Controller
{
    public function __invoke()
    {

        abort_if(Gate::denies('access_customers'), 403);

        return view('admin.customers.index');
    }

    public function details(Customer $customer)
    {
        abort_if(Gate::denies('access_customers'), 403);

        return view('admin.customers.details', compact('customer'));
    }
}
