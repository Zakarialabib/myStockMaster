<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExpenseController extends Controller
{
    /**
     * Retrieve a list of expenses with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Expense::query();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->get('_end') !== null) {
            $limit = (int) ($request->input('_end') ?? 10);
            $offset = (int) ($request->input('_start') ?? 0);
            $order = (string) ($request->input('_order') ?? 'asc');
            $sort = (string) ($request->input('_sort') ?? 'id');

            $expenses = $query
                ->orderBy($sort, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $expenses = $query->get();
        }

        return ExpenseResource::collection($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request): ExpenseResource
    {
        $expense = Expense::create($request->all());

        return new ExpenseResource($expense);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ExpenseResource|JsonResponse
    {
        $expense = Expense::find($id);

        if ($expense === null) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return new ExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, int $id): ExpenseResource
    {
        $expense = Expense::findOrFail($id);
        $expense->update($request->all());

        return new ExpenseResource($expense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
