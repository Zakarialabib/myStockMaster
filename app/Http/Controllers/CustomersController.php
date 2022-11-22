<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Customer;

class CustomersController extends Controller
{

    public function index() {
        abort_if(Gate::denies('access_customers'), 403);

        return view('admin.customers.index');
    }

    public function details(Customer $customer) {
        abort_if(Gate::denies('access_customers'), 403);

        return view('admin.customers.details', compact('customer'));
    }
}
