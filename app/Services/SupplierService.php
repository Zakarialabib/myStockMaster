<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function create(array $data): Supplier
    {
        return Supplier::query()->create($data);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);

        return $supplier;
    }
}
