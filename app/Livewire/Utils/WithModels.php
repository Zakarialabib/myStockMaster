<?php

declare(strict_types=1);

namespace App\Livewire\Utils;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\Warehouse;
use Livewire\Attributes\Computed;

trait WithModels
{
    #[Computed]
    public function customers(): array
    {
        return Customer::pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function suppliers(): array
    {
        return Supplier::pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function warehouses()
    {
        $user = auth()->user();

        if ($user) {
            $warehouses = $user?->warehouses;

            return $warehouses->pluck('name', 'id')->toArray();
        }

        return Warehouse::pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function roles()
    {
        return Role::pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function currencies()
    {
        return Currency::pluck('name', 'id')->toArray();
    }
}
