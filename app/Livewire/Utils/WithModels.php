<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;

/**
 * @property Collection<int, Customer>  $customers
 * @property Collection<int, Supplier>  $suppliers
 * @property Collection<int, Warehouse> $warehouses
 * @property int|float                  $tax_percentage
 * @property int|float                  $discount_percentage
 */
trait WithModels
{
    /**
     * @return array<int, string>
     */
    #[Computed]
    public function customers(): array
    {
        return Customer::query()->pluck('name', 'id')->toArray();
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function suppliers(): array
    {
        return Supplier::query()->pluck('name', 'id')->toArray();
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function warehouses(): array
    {
        $user = auth()->user();

        if ($user) {
            $warehouses = $user?->warehouses;

            return $warehouses->pluck('name', 'id')->toArray();
        }

        return Warehouse::query()->pluck('name', 'id')->toArray();
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function roles(): array
    {
        return Role::query()->pluck('name', 'id')->toArray();
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function currencies(): array
    {
        return Currency::query()->pluck('name', 'id')->toArray();
    }
}
