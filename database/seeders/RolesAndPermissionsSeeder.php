<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'user_access']);
        Permission::create(['name' => 'user_create']);
        Permission::create(['name' => 'user_update']);
        Permission::create(['name' => 'user_delete']);
        Permission::create(['name' => 'role_access']);
        Permission::create(['name' => 'role_create']);
        Permission::create(['name' => 'role_update']);
        Permission::create(['name' => 'role_delete']);
        Permission::create(['name' => 'permission_access']);
        Permission::create(['name' => 'permission_create']);
        Permission::create(['name' => 'permission_update']);
        Permission::create(['name' => 'permission_delete']);
        Permission::create(['name' => 'customer_access']);
        Permission::create(['name' => 'customer_show']);
        Permission::create(['name' => 'customer_create']);
        Permission::create(['name' => 'customer_update']);
        Permission::create(['name' => 'customer_delete']);
        Permission::create(['name' => 'product_access']);
        Permission::create(['name' => 'product_create']);
        Permission::create(['name' => 'product_update']);
        Permission::create(['name' => 'product_delete']);
        Permission::create(['name' => 'product_show']);
        Permission::create(['name' => 'product_import']);
        Permission::create(['name' => 'sale_access']);
        Permission::create(['name' => 'sale_create']);
        Permission::create(['name' => 'sale_show']);
        Permission::create(['name' => 'sale_update']);
        Permission::create(['name' => 'sale_delete']);
        Permission::create(['name' => 'purchase_access']);
        Permission::create(['name' => 'purchase_create']);
        Permission::create(['name' => 'purchase_update']);
        Permission::create(['name' => 'purchase_delete']);
        Permission::create(['name' => 'report_access']);
        Permission::create(['name' => 'log_access']);
        Permission::create(['name' => 'backup_access']);
        Permission::create(['name' => 'setting_access']);
        Permission::create(['name' => 'dashboard_access']);
        Permission::create(['name' => 'category_access']);
        Permission::create(['name' => 'category_create']);
        Permission::create(['name' => 'category_update']);
        Permission::create(['name' => 'category_delete']);
        Permission::create(['name' => 'brand_access']);
        Permission::create(['name' => 'brand_create']);
        Permission::create(['name' => 'brand_update']);
        Permission::create(['name' => 'brand_delete']);
        Permission::create(['name' => 'brand_show']);
        Permission::create(['name' => 'brand_import']);
        Permission::create(['name' => 'expense_access']);
        Permission::create(['name' => 'expense_show']);
        Permission::create(['name' => 'expense_create']);
        Permission::create(['name' => 'expense_update']);
        Permission::create(['name' => 'expense_delete']);
        Permission::create(['name' => 'adjustment_access']);
        Permission::create(['name' => 'adjustment_create']);
        Permission::create(['name' => 'adjustment_edit']);
        Permission::create(['name' => 'adjustment_delete']);
        Permission::create(['name' => 'printer_access']);
        Permission::create(['name' => 'printer_create']);
        Permission::create(['name' => 'printer_show']);
        Permission::create(['name' => 'printer_edit']);
        Permission::create(['name' => 'printer_delete']);
        Permission::create(['name' => 'quotation_create']);
        Permission::create(['name' => 'quotation_update']);
        Permission::create(['name' => 'quotation_delete']);
        Permission::create(['name' => 'quotation_sale']);
        Permission::create(['name' => 'print_barcodes']);
        Permission::create(['name' => 'purchase_return_access']);
        Permission::create(['name' => 'purchase_return_create']);
        Permission::create(['name' => 'purchase_return_update']);
        Permission::create(['name' => 'purchase_return_show']);
        Permission::create(['name' => 'purchase_return_delete']);
        Permission::create(['name' => 'sale_return_access']);
        Permission::create(['name' => 'sale_return_create']);
        Permission::create(['name' => 'sale_return_update']);
        Permission::create(['name' => 'sale_return_show']);
        Permission::create(['name' => 'sale_return_delete']);
        Permission::create(['name' => 'currency_access']);
        Permission::create(['name' => 'expense_categories_access']);
        Permission::create(['name' => 'expense_categories_create']);
        Permission::create(['name' => 'expense_categories_show']);
        Permission::create(['name' => 'expense_categories_edit']);
        Permission::create(['name' => 'expense_categories_delete']);
        Permission::create(['name' => 'purchase_payment_access']);
        Permission::create(['name' => 'purchase_payment_create']);
        Permission::create(['name' => 'purchase_payment_update']);
        Permission::create(['name' => 'purchase_payment_delete']);
        Permission::create(['name' => 'quotation_access']);
        Permission::create(['name' => 'supplier_access']);
        Permission::create(['name' => 'supplier_create']);
        Permission::create(['name' => 'supplier_show']);
        Permission::create(['name' => 'supplier_update']);
        Permission::create(['name' => 'supplier_import']);
        Permission::create(['name' => 'supplier_delete']);
        Permission::create(['name' => 'sale_payment_access']);
        Permission::create(['name' => 'sale_payment_create']);
        Permission::create(['name' => 'sale_payment_update']);
        Permission::create(['name' => 'sale_payment_delete']);
        Permission::create(['name' => 'warehouse_access']);
        Permission::create(['name' => 'warehouse_create']);
        Permission::create(['name' => 'warehouse_show']);
        Permission::create(['name' => 'warehouse_update']);
        Permission::create(['name' => 'warehouse_delete']);
        Permission::create(['name' => 'language_access']);
        Permission::create(['name' => 'language_create']);
        Permission::create(['name' => 'language_update']);
        Permission::create(['name' => 'language_delete']);

        $role = Role::create(['name' => 'Super Admin']);
        $role->givePermissionTo(Permission::all());
    }
}
