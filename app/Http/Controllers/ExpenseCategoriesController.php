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

    public function store(Request $request) {
        abort_if(Gate::denies('access_expense_categories'), 403);

        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'description' => 'nullable|string|max:1000'
        ]);

        ExpenseCategory::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        toast('Expense Category Created!', 'success');

        return redirect()->route('expense-categories.index');
    }


    public function edit(ExpenseCategory $expenseCategory) {
        abort_if(Gate::denies('access_expense_categories'), 403);

        return view('admin.expenses.categories.edit', compact('expenseCategory'));
    }


    public function update(Request $request, ExpenseCategory $expenseCategory) {
        abort_if(Gate::denies('access_expense_categories'), 403);

        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'description' => 'nullable|string|max:1000'
        ]);

        $expenseCategory->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        toast('Expense Category Updated!', 'info');

        return redirect()->route('expense-categories.index');
    }


    public function destroy(ExpenseCategory $expenseCategory) {
        abort_if(Gate::denies('access_expense_categories'), 403);

        if ($expenseCategory->expenses()->isNotEmpty()) {
            return back()->withErrors('Can\'t delete beacuse there are expenses associated with this category.');
        }

        $expenseCategory->delete();

        toast('Expense Category Deleted!', 'warning');

        return redirect()->route('expense-categories.index');
    }
}
