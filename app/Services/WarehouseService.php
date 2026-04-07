<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Warehouse;

class WarehouseService
{
    public function create(array $data): Warehouse
    {
        return Warehouse::query()->create($data);
    }

    public function update(Warehouse $warehouse, array $data): Warehouse
    {
        $warehouse->update($data);

        return $warehouse;
    }
}
