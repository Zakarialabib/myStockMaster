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
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'subscription_management_access',
            ],
            [
                'id'    => 18,
                'title' => 'client_product_management',
            ],
            [
                'id'    => 19,
                'title' => 'admin_dashboard',
            ],
            [
                'id'    => 20,
                'title' => 'client_dashboard',
            ],
            [
                'id'    => 21,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => 22,
                'title' => 'user_alert_edit',
            ],
            [
                'id'    => 23,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => 24,
                'title' => 'user_alert_access',
            ],	 
            [
                'id'    => 25,
                'title' => 'admin_product_delete',
            ],
            [
                'id'    => 26,
                'title' => 'admin_settings_management',
            ],
            [
                'id'    => 27,
                'title' => 'admin_stock_management',
            ],
            [
                'id'    => 28,
                'title' => 'admin_payment_management',
            ],
            [
                'id'    => 29,
                'title' => 'admin_order_management',
            ],
            [
                'id'    => 30,
                'title' => 'client_product_management',
            ],
            [
                'id'    => 31,
                'title' => 'admin_page_management',
            ],
            [
                'id'    => 32,
                'title' => 'admin_user_alert_delete',
            ],
            [
                'id'    => 33,
                'title' => 'admin_order_delete',
            ],
            [
                'id'    => 34,
                'title' => 'admin_subscription_create',
            ],
            [
                'id'    => 35,
                'title' => 'admin_subscription_edit',
            ],
            [
                'id'    => 36,
                'title' => 'admin_subscription_delete',
            ],
            [
                'id'    => 37,
                'title' => 'vendor_dashboard',
            ],
            [
                'id'    => 38,
                'title' => 'vendor_order_management',
            ],
            [
                'id'    => 39,
                'title' => 'vendor_product_management',
            ],
            [
                'id'    => 40,
                'title' => 'admin_phone_management',
            ],
            [
                'id'    => 41,
                'title' => 'admin_order_payments',
            ],
            [
                'id'    => 42,
                'title' => 'admin_brand_management',
            ],
            [
                'id'    => 43,
                'title' => 'admin_phone_create',
            ],
            [
                'id'    => 44,
                'title' => 'admin_phone_edit',
            ],
            [
                'id'    => 45,
                'title' => 'admin_phone_show',
            ],
            [
                'id'    => 46,
                'title' => 'admin_reports',
            ],
        ];

        Permission::insert($permissions);
    }
}
