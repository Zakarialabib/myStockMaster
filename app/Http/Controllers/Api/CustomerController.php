<?php

declare(strict_types=1);

namespace app\Http\Controllers\Api;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Retrieve a list of Customer with optional filters and pagination.
     */
    #[Get('/api/customers', name: 'api.customers.index')]
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

            // capture brand_id filter
            // $brand_id = $request->get('brand_id') ? $request->get('brand_id')  : '';
            // if ($brand_id !== '') {
            //     $where_raw .= " AND (brand_id =  $brand_id)";
            // }
            // capture sort fields
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

        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Post('/api/customers', name: 'api.customers.store')]
    #[Middleware('api')]
    public function store(StoreCustomerRequest $request): CustomerResource
    {
        $customer = Customer::create($request->all());

        return new CustomerResource($customer);
    }

    /**
     * Display the specified resource.
     */
    #[Get('/api/customers/{id}', name: 'api.customers.show')]
    #[Middleware('api')]
    public function show(int $id): CustomerResource|JsonResponse
    {
        $Customer = Customer::find($id);

        if (is_null($Customer)) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return new CustomerResource($Customer);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Put('/api/customers/{id}', name: 'api.customers.update')]
    #[Middleware('api')]
    public function update(UpdateCustomerRequest $request, int $id): CustomerResource
    {
        $Customer = Customer::findOrFail($id);
        $Customer->update($request->all());

        return new CustomerResource($Customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
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
