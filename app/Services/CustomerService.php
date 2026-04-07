<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function create(array $data): Customer
    {
        return Customer::query()->create($data);
    }

    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer;
    }
}
