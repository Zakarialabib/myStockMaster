<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class CustomersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('customer_access'), 403);

        return view('admin.customers.index');
    }

    public function show($customer)
    {
        abort_if(Gate::denies('customer_show'), 403);

        $customer = Customer::whereUuid($customer)->first();

        return view('admin.customers.details', compact('customer'));
    }
}
