<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\ExpenseCategory;

class ExpenseCategoriesController extends Controller
{

    public function index() {
        abort_if(Gate::denies('access_expense_categories'), 403);

        return view('admin.expenses.categories.index');
    }

}
