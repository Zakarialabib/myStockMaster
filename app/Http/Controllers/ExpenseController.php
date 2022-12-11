<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('access_expenses'), 403);

        return view('admin.expenses.index');
    }
}
