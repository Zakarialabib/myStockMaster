<?php

declare(strict_types=1);

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ExpenseController extends BaseController
{
    /**
     * Retrieve a list of Expense with optional filters and pagination.
     *
     * @param  Request  $request
     *
     */
    /**
     * Retrieve a list of expenses with optional filters and pagination.
     *
     * @param  Request  $request
     *
     */
    public function index(Request $request)
    {
        try {
            if ($request->get('_end') !== null) {
                $limit = $request->get('_end') ? $request->get('_end') : 10;
                $offset = $request->get('_start') ? $request->get('_start') : 0;

                $order = $request->get('_order') ? $request->get('_order') : 'asc';
                $sort = $request->get('_sort') ? $request->get('_sort') : 'id';
                //Filters
                $where_raw = ' 1=1 ';

                //capture category_id filter
                $category_id = $request->get('category_id') ? $request->get('category_id') : '';

                if ($category_id !== '') {
                    $where_raw .= " AND (category_id =  $category_id)";
                }
                //capture sort fields
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

            return $this->sendResponse($expenses, 'Expense List');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $input = $request->all();
            $Expense = Expense::create($input);
            DB::commit();

            return $this->sendResponse($Expense, 'Expense created successfully');
        } catch (Exception $e) {
            DB::rollback();

            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     */
    public function show($id)
    {
        try {
            $Expense = Expense::find($id);

            if (is_null($Expense)) {
                return $this->sendError('Expense not found');
            } else {
                return $this->sendResponse($Expense, 'Expense retrieved successfully');
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request, $id)
    {
        $Expense = Expense::findOrFail($id);
        $Expense->update($request->all());

        return new ExpenseResource($Expense);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
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
