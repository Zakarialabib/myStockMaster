<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class CustomersController extends Controller
{

    public function index() {
        abort_if(Gate::denies('access_customers'), 403);

        return view('admin.customers.index');
    }
    
}
