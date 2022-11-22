<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class CurrencyController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('access_currencies'), 403);

        return view('admin.currency.index');
    }
}
