<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            [
                'id'         => 1,
                'guard_name'       => 'web',
                'name' => 'user_access',
            ],
            [
                'id'         => 2,
                'guard_name'       => 'web',
                'name' => 'user_create',
            ],
            [
                'id'         => 3,
                'guard_name'       => 'web',
                'name' => 'user_update',
            ],
            [
                'id'         => 4,
                'guard_name'       => 'web',
                'name' => 'user_delete',
            ],
            [
                'id'         => 5,
                'guard_name'       => 'web',
                'name' => 'role_access',
            ],
            [
                'id'         => 6,
                'guard_name'       => 'web',
                'name' => 'role_create',
            ],
            [
                'id'         => 7,
                'guard_name'       => 'web',
                'name' => 'role_update',
            ],
            [
                'id'         => 8,
                'guard_name'       => 'web',
                'name' => 'role_delete',
            ],
            [
                'id'         => 9,
                'guard_name'       => 'web',
                'name' => 'permission_access',
            ],
            [
                'id'         => 10,
                'guard_name'       => 'web',
                'name' => 'permission_create',
            ],
            [
                'id'         => 11,
                'guard_name'       => 'web',
                'name' => 'permission_update',
            ],
            [
                'id'         => 12,
                'guard_name'       => 'web',
                'name' => 'permission_delete',
            ],
            [
                'id'         => 13,
                'guard_name'       => 'web',
                'name' => 'customer_access',
            ],
            [
                'id'         => 14,
                'guard_name'       => 'web',
                'name' => 'customer_create',
            ],
            [
                'id'         => 15,
                'guard_name'       => 'web',
                'name' => 'customer_update',
            ],
            [
                'id'         => 16,
                'guard_name'       => 'web',
                'name' => 'customer_delete',
            ],
            [
                'id'         => 17,
                'guard_name'       => 'web',
                'name' => 'product_access',
            ],
            [
                'id'         => 18,
                'guard_name'       => 'web',
                'name' => 'product_create',
            ],
            [
                'id'         => 19,
                'guard_name'       => 'web',
                'name' => 'product_update',
            ],
            [
                'id'         => 20,
                'guard_name'       => 'web',
                'name' => 'product_delete',
            ],
            [
                'id'         => 21,
                'guard_name'       => 'web',
                'name' => 'sale_access',
            ],
            [
                'id'         => 22,
                'guard_name'       => 'web',
                'name' => 'sale_create',
            ],
            [
                'id'         => 23,
                'guard_name'       => 'web',
                'name' => 'sale_update',
            ],
            [
                'id'         => 24,
                'guard_name'       => 'web',
                'name' => 'sale_delete',
            ],
            [
                'id'         => 25,
                'guard_name'       => 'web',
                'name' => 'purchase_access',
            ],
            [
                'id'         => 26,
                'guard_name'       => 'web',
                'name' => 'purchase_create',
            ],
            [
                'id'         => 27,
                'guard_name'       => 'web',
                'name' => 'purchase_update',
            ],
            [
                'id'         => 28,
                'guard_name'       => 'web',
                'name' => 'purchase_delete',
            ],
            [
                'id'         => 29,
                'guard_name'       => 'web',
                'name' => 'report_access',
            ],
            [
                'id'         => 30,
                'guard_name'       => 'web',
                'name' => 'report_create',
            ],
            [
                'id'         => 31,
                'guard_name'       => 'web',
                'name' => 'report_update',
            ],
            [
                'id'         => 32,
                'guard_name'       => 'web',
                'name' => 'report_delete',
            ],
            [
                'id'         => 33,
                'guard_name'       => 'web',
                'name' => 'setting_access',
            ],
            [
                'id'         => 34,
                'guard_name'       => 'web',
                'name' => 'dashboard_access',
            ],
            [
                'id'         => 35,
                'guard_name'       => 'web',
                'name' => 'category_access',
            ],
            [
                'id'         => 36,
                'guard_name'       => 'web',
                'name' => 'category_create',
            ],
            [
                'id'         => 37,
                'guard_name'       => 'web',
                'name' => 'category_update',
            ],
            [
                'id'         => 38,
                'guard_name'       => 'web',
                'name' => 'category_delete',
            ],
            [
                'id'         => 39,
                'guard_name'       => 'web',
                'name' => 'brand_access',
            ],
            [
                'id'         => 40,
                'guard_name'       => 'web',
                'name' => 'brand_create',
            ],
            [
                'id'         => 41,
                'guard_name'       => 'web',
                'name' => 'brand_update',
            ],
            [
                'id'         => 42,
                'guard_name'       => 'web',
                'name' => 'brand_delete',
            ],
            [
                'id'         => 43,
                'guard_name'       => 'web',
                'name' => 'expense_access',
            ],
            [
                'id'         => 44,
                'guard_name'       => 'web',
                'name' => 'expense_create',
            ],
            [
                'id'         => 45,
                'guard_name'       => 'web',
                'name' => 'expense_update',
            ],
            [
                'id'         => 46,
                'guard_name'       => 'web',
                'name' => 'expense_delete',
            ],
            [
                'id'         => 47,
                'guard_name'       => 'web',
                'name' => 'adjustment_access',
            ],
            [
                'id'         => 48,
                'guard_name'       => 'web',
                'name' => 'adjustment_edit',
            ],
            [
                'id'         => 49,
                'guard_name'       => 'web',
                'name' => 'adjustment_create',
            ],
            [
                'id'         => 50,
                'guard_name'       => 'web',
                'name' => 'expense_show',
            ],
            [
                'id'         => 51,
                'guard_name'       => 'web',
                'name' => 'printer_access',
            ],
            [
                'id'         => 52,
                'guard_name'       => 'web',
                'name' => 'printer_show',
            ],
            [
                'id'         => 53,
                'guard_name'       => 'web',
                'name' => 'printer_edit',
            ],
            [
                'id'         => 54,
                'guard_name'       => 'web',
                'name' => 'printer_delete',
            ],
            [
                'id'         => 55,
                'guard_name'       => 'web',
                'name' => 'quotation_create',
            ],
            [
                'id'         => 56,
                'guard_name'       => 'web',
                'name' => 'quotation_update',
            ],
            [
                'id'         => 57,
                'guard_name'       => 'web',
                'name' => 'quotation_delete',
            ],
            [
                'id'         => 58,
                'guard_name'       => 'web',
                'name' => 'quotation_sale',
            ],
            [
                'id'         => 60,
                'guard_name'       => 'web',
                'name' => 'purchase_return_access',
            ],
            [
                'id'         => 61,
                'guard_name'       => 'web',
                'name' => 'purchase_return_create',
            ],
            [
                'id'         => 62,
                'guard_name'       => 'web',
                'name' => 'purchase_return_update',
            ],
            [
                'id'         => 63,
                'guard_name'       => 'web',
                'name' => 'purchase_return_show',
            ],
            [
                'id'         => 64,
                'guard_name'       => 'web',
                'name' => 'purchase_return_delete',
            ],
            [
                'id'         => 65,
                'guard_name'       => 'web',
                'name' => 'sale_return_access',
            ],
            [
                'id'         => 66,
                'guard_name'       => 'web',
                'name' => 'sale_return_create',
            ],
            [
                'id'         => 67,
                'guard_name'       => 'web',
                'name' => 'sale_return_update',
            ],
            [
                'id'         => 68,
                'guard_name'       => 'web',
                'name' => 'sale_return_show',
            ],
            [
                'id'         => 69,
                'guard_name'       => 'web',
                'name' => 'sale_return_delete',
            ],
            [
                'id'         => 70,
                'guard_name'       => 'web',
                'name' => 'access_languages',
            ],

        ];

        Permission::insert($permissions);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
