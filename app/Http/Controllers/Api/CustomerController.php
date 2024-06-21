<?php

declare(strict_types=1);

namespace app\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomerController extends BaseController
{
    /**
     * Retrieve a list of Customer with optional filters and pagination.
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

                //capture brand_id filter
                // $brand_id = $request->get('brand_id') ? $request->get('brand_id')  : '';
                // if ($brand_id !== '') {
                //     $where_raw .= " AND (brand_id =  $brand_id)";
                // }
                //capture sort fields
                $sort_array = explode(',', $sort);

                if (count($sort_array) > 0) {
                    // retireve ordered and limit customers list
                    $customers = Customer::whereRaw($where_raw)
                        // ->orderByRaw("COALESCE($sort)")
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
                } else {
                    // retireve ordered and limit customers list
                    $customers = Customer::orderBy($sort, $order)
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
                }
            } else {
                // retireve all customers
                $customers = Customer::get();
            }

            return $this->sendResponse($customers, 'Customer List');
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
            $customer = Customer::create($input);
            DB::commit();

            return $this->sendResponse($customer, 'Customer created successfully');
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
            $Customer = Customer::find($id);

            if (is_null($Customer)) {
                return $this->sendError('Customer not found');
            } else {
                return $this->sendResponse($Customer, 'Customer retrieved successfully');
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
        $Customer = Customer::findOrFail($id);
        $Customer->update($request->all());

        return new CustomerResource($Customer);
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
            $Customer = Customer::findOrFail($id);
            $Customer->delete();

            return $this->sendResponse($Customer, 'Customer deleted successfully');
        } catch (Exception $e) {
            DB::rollback();

            return $this->sendError($e->getMessage());
        }
    }
}
