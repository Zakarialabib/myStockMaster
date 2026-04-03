<?php

declare(strict_types=1);

namespace app\Http\Controllers\Api;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Retrieve a list of Expense with optional filters and pagination.
     */
    /**
     * Retrieve a list of expenses with optional filters and pagination.
     */
    #[Get('/api/expenses', name: 'api.expenses.index')]
    #[Middleware('api')]
    public function index(Request $request): AnonymousResourceCollection
    {

        if ($request->get('_end') !== null) {
            $limit = $request->get('_end') ? $request->get('_end') : 10;
            $offset = $request->get('_start') ? $request->get('_start') : 0;

            $order = $request->get('_order') ? $request->get('_order') : 'asc';
            $sort = $request->get('_sort') ? $request->get('_sort') : 'id';
            // Filters
            $where_raw = ' 1=1 ';

            // capture category_id filter
            $category_id = $request->get('category_id') ? $request->get('category_id') : '';

            if ($category_id !== '') {
                $where_raw .= " AND (category_id =  $category_id)";
            }
            // capture sort fields
            $sort_array = explode(',', $sort);

            if (count($sort_array) > 0) {
                // retireve ordered and limit expenses list
                $expenses = Expense::whereRaw($where_raw)
                    // ->orderByRaw("COALESCE($sort)")
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                // retireve ordered and limit expenses list
                $expenses = Expense::orderBy($sort, $order)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }
        } else {
            // retireve all expenses
            $expenses = Expense::get();
        }

        return ExpenseResource::collection($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Post('/api/expenses', name: 'api.expenses.store')]
    #[Middleware('api')]
    public function store(StoreExpenseRequest $request): ExpenseResource
    {
        $Expense = Expense::create($request->all());

        return new ExpenseResource($Expense);
    }

    /**
     * Display the specified resource.
     */
    #[Get('/api/expenses/{id}', name: 'api.expenses.show')]
    #[Middleware('api')]
    public function show(int $id): ExpenseResource|JsonResponse
    {
        $Expense = Expense::find($id);

        if (is_null($Expense)) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        return new ExpenseResource($Expense);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Put('/api/expenses/{id}', name: 'api.expenses.update')]
    #[Middleware('api')]
    public function update(UpdateExpenseRequest $request, int $id): ExpenseResource
    {
        $Expense = Expense::findOrFail($id);
        $Expense->update($request->all());

        return new ExpenseResource($Expense);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        try {
            $Expense = Expense::findOrFail($id);
            $Expense->delete();

            return $this->sendResponse($Expense, 'Expense deleted successfully');
        } catch (Exception $e) {
            DB::rollback();

            return $this->sendError($e->getMessage());
        }
    }
}
