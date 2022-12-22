<?php

declare(strict_types=1);

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
                'id'         => 1,
                'name'       => 'user access',
                'guard_name' => 'user_access',
            ],
            [
                'id'         => 2,
                'name'       => 'user create',
                'guard_name' => 'user_create',
            ],
            [
                'id'         => 3,
                'name'       => 'user update',
                'guard_name' => 'user_update',
            ],
            [
                'id'         => 4,
                'name'       => 'user delete',
                'guard_name' => 'user_delete',
            ],
            [
                'id'         => 5,
                'name'       => 'role access',
                'guard_name' => 'role_access',
            ],
            [
                'id'         => 6,
                'name'       => 'role create',
                'guard_name' => 'role_create',
            ],
            [
                'id'         => 7,
                'name'       => 'role update',
                'guard_name' => 'role_update',
            ],
            [
                'id'         => 8,
                'name'       => 'role delete',
                'guard_name' => 'role_delete',
            ],
            [
                'id'         => 9,
                'name'       => 'permission access',
                'guard_name' => 'permission_access',
            ],
            [
                'id'         => 10,
                'name'       => 'permission create',
                'guard_name' => 'permission_create',
            ],
            [
                'id'         => 11,
                'name'       => 'permission update',
                'guard_name' => 'permission_update',
            ],
            [
                'id'         => 12,
                'name'       => 'permission delete',
                'guard_name' => 'permission_delete',
            ],
            [
                'id'         => 13,
                'name'       => 'customer access',
                'guard_name' => 'customer_access',
            ],
            [
                'id'         => 14,
                'name'       => 'customer create',
                'guard_name' => 'customer_create',
            ],
            [
                'id'         => 15,
                'name'       => 'customer update',
                'guard_name' => 'customer_update',
            ],
            [
                'id'         => 16,
                'name'       => 'customer delete',
                'guard_name' => 'customer_delete',
            ],
            [
                'id'         => 17,
                'name'       => 'product access',
                'guard_name' => 'product_access',
            ],
            [
                'id'         => 18,
                'name'       => 'product create',
                'guard_name' => 'product_create',
            ],
            [
                'id'         => 19,
                'name'       => 'product update',
                'guard_name' => 'product_update',
            ],
            [
                'id'         => 20,
                'name'       => 'product delete',
                'guard_name' => 'product_delete',
            ],
            [
                'id'         => 21,
                'name'       => 'sale access',
                'guard_name' => 'sale_access',
            ],
            [
                'id'         => 22,
                'name'       => 'sale create',
                'guard_name' => 'sale_create',
            ],
            [
                'id'         => 23,
                'name'       => 'sale update',
                'guard_name' => 'sale_update',
            ],
            [
                'id'         => 24,
                'name'       => 'sale delete',
                'guard_name' => 'sale_delete',
            ],
            [
                'id'         => 25,
                'name'       => 'purchase access',
                'guard_name' => 'purchase_access',
            ],
            [
                'id'         => 26,
                'name'       => 'purchase create',
                'guard_name' => 'purchase_create',
            ],
            [
                'id'         => 27,
                'name'       => 'purchase update',
                'guard_name' => 'purchase_update',
            ],
            [
                'id'         => 28,
                'name'       => 'purchase delete',
                'guard_name' => 'purchase_delete',
            ],
            [
                'id'         => 29,
                'name'       => 'report access',
                'guard_name' => 'report_access',
            ],
            [
                'id'         => 30,
                'name'       => 'report create',
                'guard_name' => 'report_create',
            ],
            [
                'id'         => 31,
                'name'       => 'report update',
                'guard_name' => 'report_update',
            ],
            [
                'id'         => 32,
                'name'       => 'report delete',
                'guard_name' => 'report_delete',
            ],
            [
                'id'         => 33,
                'name'       => 'setting access',
                'guard_name' => 'setting_access',
            ],
            [
                'id'         => 34,
                'name'       => 'dashboard access',
                'guard_name' => 'dashboard_access',
            ],
            [
                'id'         => 35,
                'name'       => 'category access',
                'guard_name' => 'category_access',
            ],
            [
                'id'         => 36,
                'name'       => 'category delete',
                'guard_name' => 'category_create',
            ],
            [
                'id'         => 37,
                'name'       => 'category update',
                'guard_name' => 'category_update',
            ],
            [
                'id'         => 38,
                'name'       => 'category delete',
                'guard_name' => 'category_delete',
            ],
            [
                'id'         => 39,
                'name'       => 'brand access',
                'guard_name' => 'brand_access',
            ],
            [
                'id'         => 40,
                'name'       => 'brand create',
                'guard_name' => 'brand_create',
            ],
            [
                'id'         => 41,
                'name'       => 'brand update',
                'guard_name' => 'brand_update',
            ],
            [
                'id'         => 42,
                'name'       => 'brand delete',
                'guard_name' => 'brand_delete',
            ],
            [
                'id'         => 43,
                'name'       => 'expense access',
                'guard_name' => 'expense_access',
            ],
            [
                'id'         => 44,
                'name'       => 'expense create',
                'guard_name' => 'expense_create',
            ],
            [
                'id'         => 45,
                'name'       => 'expense update',
                'guard_name' => 'expense_update',
            ],
            [
                'id'         => 46,
                'name'       => 'expense delete',
                'guard_name' => 'expense_delete',
            ],
            [
                'id'         => 47,
                'name'       => 'adjustment access',
                'guard_name' => 'adjustment_access',
            ],
            [
                'id'         => 48,
                'name'       => 'adjustment edit',
                'guard_name' => 'adjustment_edit',
            ],
            [
                'id'         => 49,
                'name'       => 'adjustment create',
                'guard_name' => 'adjustment_create',
            ],
            [
                'id'         => 50,
                'name'       => 'adjustment show',
                'guard_name' => 'expense_show',
            ],
            [
                'id'         => 51,
                'name'       => 'printer access',
                'guard_name' => 'printer_access',
            ],
            [
                'id'         => 52,
                'name'       => 'printer show',
                'guard_name' => 'printer_show',
            ],
            [
                'id'         => 53,
                'name'       => 'printer edit',
                'guard_name' => 'printer_edit',
            ],
            [
                'id'         => 54,
                'name'       => 'printer delete',
                'guard_name' => 'printer_delete',
            ],
            [
                'id'         => 55,
                'name'       => 'quotation create',
                'guard_name' => 'quotation_create',
            ],
            [
                'id'         => 56,
                'name'       => 'quotation update',
                'guard_name' => 'quotation_update',
            ],
            [
                'id'         => 57,
                'name'       => 'quotation delete',
                'guard_name' => 'quotation_delete',
            ],
            [
                'id'         => 58,
                'name'       => 'quotation sale',
                'guard_name' => 'quotation_sale',
            ],
            [
                'id'         => 58,
                'name'       => 'quotation_access',
                'guard_name' => 'quotation_delete',
            ],
            [
                'id'         => 59,
                'name'       => 'purchase return access',
                'guard_name' => 'purchase_return_access',
            ],
            [
                'id'         => 60,
                'name'       => 'purchase return create',
                'guard_name' => 'purchase_return_create',
            ],
            [
                'id'         => 61,
                'name'       => 'purchase return update',
                'guard_name' => 'purchase_return_update',
            ],
            [
                'id'         => 62,
                'name'       => 'purchase return show',
                'guard_name' => 'purchase_return_show',
            ],
            [
                'id'         => 63,
                'name'       => 'purchase return delete',
                'guard_name' => 'purchase_return_delete',
            ],
            [
                'id'         => 64,
                'name'       => 'sale return access',
                'guard_name' => 'sale_return_access',
            ],
            [
                'id'         => 65,
                'name'       => 'sale return create',
                'guard_name' => 'sale_return_create',
            ],
            [
                'id'         => 65,
                'name'       => 'sale return update',
                'guard_name' => 'sale_return_update',
            ],
            [
                'id'         => 66,
                'name'       => 'sale return show',
                'guard_name' => 'sale_return_show',
            ],
            [
                'id'         => 64,
                'name'       => 'sale return delete',
                'guard_name' => 'sale_return_delete',
            ],
            [
                'id'         => 65,
                'name'       => 'access language',
                'guard_name' => 'access_languages',
            ],

        ];

        Permission::insert($permissions);
    }
}
