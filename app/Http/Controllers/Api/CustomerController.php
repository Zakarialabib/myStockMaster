<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    /**
     * Retrieve a list of customers with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->get('_end') !== null) {
            $limit = (int) ($request->input('_end') ?? 10);
            $offset = (int) ($request->input('_start') ?? 0);
            $order = (string) ($request->input('_order') ?? 'asc');
            $sort = (string) ($request->input('_sort') ?? 'id');

            $customers = Customer::query()
                ->orderBy($sort, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $customers = Customer::query()->get();
        }

        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $storeCustomerRequest): CustomerResource
    {
        $customer = Customer::query()->create($storeCustomerRequest->all());

        return new CustomerResource($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): CustomerResource|JsonResponse
    {
        $customer = Customer::query()->find($id);

        if ($customer === null) {
            return new \Illuminate\Http\JsonResponse(['message' => 'Customer not found'], 404);
        }

        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $updateCustomerRequest, int $id): CustomerResource
    {
        $customer = Customer::query()->findOrFail($id);
        $customer->update($updateCustomerRequest->all());

        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $customer = Customer::query()->findOrFail($id);
        $customer->delete();

        return new \Illuminate\Http\JsonResponse(['message' => 'Customer deleted successfully']);
    }
}
