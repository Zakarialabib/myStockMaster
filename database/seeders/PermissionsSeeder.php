<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'id' => 1,
                'name' => 'user access',
                'guard_name' => 'user_access',
            ],
            [
                'id' => 2,
                'name' => 'user create',
                'guard_name' => 'user_create',
            ],
            [
                'id' => 3,
                'name' => 'user update',
                'guard_name' => 'user_update',
            ],
            [
                'id' => 4,
                'name' => 'user delete',
                'guard_name' => 'user_delete',
            ],
            [
                'id' => 5,
                'name' => 'role access',
                'guard_name' => 'role_access',
            ],
            [
                'id' => 6,
                'name' => 'role create',
                'guard_name' => 'role_create',
            ],
            [
                'id' => 7,
                'name' => 'role update',
                'guard_name' => 'role_update',
            ],
            [
                'id' => 8,
                'name' => 'role delete',
                'guard_name' => 'role_delete',
            ],
            [
                'id' => 9,
                'name' => 'permission access',
                'guard_name' => 'permission_access',
            ],
            [
                'id' => 10,
                'name' => 'permission create',
                'guard_name' => 'permission_create',
            ],
            [
                'id' => 11,
                'name' => 'permission update',
                'guard_name' => 'permission_update',
            ],
            [
                'id' => 12,
                'name' => 'permission delete',
                'guard_name' => 'permission_delete',
            ],
            [
                'id' => 13,
                'name' => 'customer access',
                'guard_name' => 'customer_access',
            ],
            [
                'id' => 14,
                'name' => 'customer create',
                'guard_name' => 'customer_create',
            ],
            [
                'id' => 15,
                'name' => 'customer update',
                'guard_name' => 'customer_update',
            ],
            [
                'id' => 16,
                'name' => 'customer_delete',
            ],
            [
                'id' => 17,
                'name' => 'product_access',
            ],
            [
                'id' => 18,
                'name' => 'product_create',
            ],
            [
                'id' => 19,
                'name' => 'product_update',
            ],
            [
                'id' => 20,
                'name' => 'product_delete',
            ],
            [
                'id' => 21,
                'name' => 'sale_access',
            ],
            [
                'id' => 22,
                'name' => 'sale_create',
            ],
            [
                'id' => 23,
                'name' => 'sale_update',
            ],
            [
                'id' => 24,
                'name' => 'sale_delete',
            ],
            [
                'id' => 25,
                'name' => 'purchase_access',
            ],
            [
                'id' => 26,
                'name' => 'purchase_create',
            ],
            [
                'id' => 27,
                'name' => 'purchase_update',
            ],
            [
                'id' => 28,
                'name' => 'purchase_delete',
            ],
            [
                'id' => 29,
                'name' => 'report_access',
            ],
            [
                'id' => 30,
                'name' => 'report_create',
            ],
            [
                'id' => 31,
                'name' => 'report_update',
            ],
            [
                'id' => 32,
                'name' => 'report_delete',
            ],
            [
                'id' => 33,
                'name' => 'setting_access',
            ],
            [
                'id' => 34,
                'name' => 'dashboard_access',
            ],
            [
                'id' => 35,
                'name' => 'category_access',
            ],
            [
                'id' => 36,
                'name' => 'category_create',
            ],
            [
                'id' => 37,
                'name' => 'category_update',
            ],
            [
                'id' => 38,
                'name' => 'category_delete',
            ],
            [
                'id' => 39,
                'name' => 'brand_access',
            ],
            [
                'id' => 40,
                'name' => 'brand_create',
            ],
            [
                'id' => 41,
                'name' => 'brand_update',
            ],
            [
                'id' => 42,
                'name' => 'brand_delete',
            ],
            [
                'id' => 43,
                'name' => 'expense_access',
            ],
            [
                'id' => 44,
                'name' => 'expense_create',
            ],
            [
                'id' => 45,
                'name' => 'expense_update',
            ],
            [
                'id' => 46,
                'name' => 'expense_delete',
            ],
            [
                'id' => 47,
                'name' => 'adjustment_access',
            ],
            [
                'id' => 48,
                'name' => 'adjustment_edit',
            ],
            [
                'id' => 49,
                'name' => 'adjustment_create',
            ],
            [
                'id' => 50,
                'name' => 'adjustment_show',
            ],
            [
                'id' => 51,
                'name' => 'printer_access',
            ],
            [
                'id' => 52,
                'name' => 'printer_show',
            ],
            [
                'id' => 53,
                'name' => 'printer_edit',
            ],
            [
                'id' => 54,
                'name' => 'printer_delete',
            ],
            [
                'id' => 55,
                'name' => 'adjustment_access',
            ],
            [
                'id' => 56,
                'name' => 'quotation_create',
            ],
            [
                'id' => 57,
                'name' => 'quotation_update',
            ],
            [
                'id' => 58,
                'name' => 'quotation_delete',
            ],
            [
                'id' => 58,
                'name' => 'quotation_sale',
            ],

        ];

        Permission::insert($permissions);
    }
}
