<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserWarehouse;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'city' => $data['city'] ?? null,
            'country' => $data['country'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        if (! empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        if (! empty($data['warehouse_id'])) {
            foreach ($data['warehouse_id'] as $warehouseId) {
                UserWarehouse::create([
                    'user_id' => $user->id,
                    'warehouse_id' => $warehouseId,
                ]);
            }
        }

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'city' => $data['city'] ?? null,
            'country' => $data['country'] ?? null,
            'address' => $data['address'] ?? null,
        ];

        if (! empty($data['password']) && $data['password'] !== $user->password) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        if (isset($data['warehouse_id'])) {
            $user->warehouses()->sync($data['warehouse_id']);
        }

        if (! empty($data['role'])) {
            $user->syncRoles($data['role']);
        }

        return $user;
    }
}
