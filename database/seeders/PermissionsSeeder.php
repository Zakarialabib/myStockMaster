<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

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
                'id'    => 1,
                'name' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'name' => 'permission_create',
            ],
            [
                'id'    => 3,
                'name' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'name' => 'permission_show',
            ],
            [
                'id'    => 5,
                'name' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'name' => 'permission_access',
            ],
            [
                'id'    => 7,
                'name' => 'role_create',
            ],
            [
                'id'    => 8,
                'name' => 'role_edit',
            ],
            [
                'id'    => 9,
                'name' => 'role_show',
            ],
            [
                'id'    => 10,
                'name' => 'role_delete',
            ],
            [
                'id'    => 11,
                'name' => 'role_access',
            ],
            [
                'id'    => 12,
                'name' => 'user_create',
            ],
            [
                'id'    => 13,
                'name' => 'user_edit',
            ],
            [
                'id'    => 14,
                'name' => 'user_show',
            ],
            [
                'id'    => 15,
                'name' => 'user_delete',
            ],
            [
                'id'    => 16,
                'name' => 'user_access',
            ],
            [
                'id'    => 17,
                'name' => 'subscription_management_access',
            ],
            [
                'id'    => 18,
                'name' => 'client_product_management',
            ],
            [
                'id'    => 19,
                'name' => 'admin_dashboard',
            ],
            [
                'id'    => 20,
                'name' => 'client_dashboard',
            ],
            [
                'id'    => 21,
                'name' => 'user_alert_show',
            ],
            [
                'id'    => 22,
                'name' => 'user_alert_edit',
            ],
            [
                'id'    => 23,
                'name' => 'user_alert_create',
            ],
            [
                'id'    => 24,
                'name' => 'user_alert_access',
            ],	 
            [
                'id'    => 25,
                'name' => 'admin_product_delete',
            ],
            [
                'id'    => 26,
                'name' => 'admin_settings_management',
            ],
            [
                'id'    => 27,
                'name' => 'admin_stock_management',
            ],
            [
                'id'    => 28,
                'name' => 'admin_payment_management',
            ],
            [
                'id'    => 29,
                'name' => 'admin_order_management',
            ],
            [
                'id'    => 30,
                'name' => 'client_product_management',
            ],
            [
                'id'    => 31,
                'name' => 'admin_page_management',
            ],
            [
                'id'    => 32,
                'name' => 'admin_user_alert_delete',
            ],
            [
                'id'    => 33,
                'name' => 'admin_order_delete',
            ],
            [
                'id'    => 34,
                'name' => 'admin_subscription_create',
            ],
            [
                'id'    => 35,
                'name' => 'admin_subscription_edit',
            ],
            [
                'id'    => 36,
                'name' => 'admin_subscription_delete',
            ],
            [
                'id'    => 37,
                'name' => 'vendor_dashboard',
            ],
            [
                'id'    => 38,
                'name' => 'vendor_order_management',
            ],
            [
                'id'    => 39,
                'name' => 'vendor_product_management',
            ],
            [
                'id'    => 40,
                'name' => 'admin_phone_management',
            ],
            [
                'id'    => 41,
                'name' => 'admin_order_payments',
            ],
            [
                'id'    => 42,
                'name' => 'admin_brand_management',
            ],
            [
                'id'    => 43,
                'name' => 'admin_phone_create',
            ],
            [
                'id'    => 44,
                'name' => 'admin_phone_edit',
            ],
            [
                'id'    => 45,
                'name' => 'admin_phone_show',
            ],
            [
                'id'    => 46,
                'name' => 'admin_reports',
            ],
            
            
        ];

        Permission::insert($permissions);
    }
}
