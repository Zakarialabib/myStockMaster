<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class ExpenseCategoriesController extends Controller
{
    public function __invoke()
    {
        abort_if(Gate::denies('access_expense_categories'), 403);

        return view('admin.expenses.categories.index');
    }
}
