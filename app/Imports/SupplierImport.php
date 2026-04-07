<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    /**
     * @return Supplier
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $name = $row['name'];

        // Check if a record with the same name already exists
        $existingRecord = Supplier::query()->where('name', $name)->first();

        // If it doesn't exist, create a new record
        if (! $existingRecord) {
            $attributes = [
                'name' => $name,
                'phone' => $row['phone'],
                'email' => $row['email'] ?? null,
                'city' => $row['city'] ?? null,
                'address' => $row['address'] ?? null,
                'tax_number' => $row['tax_number'] ?? null,
                'uuid' => Str::uuid(), // Generate the UUID only on creation
            ];

            return new Supplier($attributes);
        }

        // If it already exists, update the existing record
        $existingRecord->update([
            'phone' => $row['phone'],
            'email' => $row['email'] ?? null,
            'city' => $row['city'] ?? null,
            'address' => $row['address'] ?? null,
            'tax_number' => $row['tax_number'] ?? null,
        ]);

        return $existingRecord;
    }
}
