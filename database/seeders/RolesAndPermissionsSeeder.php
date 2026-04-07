<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Force clear tables to avoid UNIQUE constraint issues in SQLite
            DB::connection('nativephp')->statement('DELETE FROM role_has_permissions');
            DB::connection('nativephp')->statement('DELETE FROM model_has_roles');
            DB::connection('nativephp')->statement('DELETE FROM model_has_permissions');
            DB::connection('nativephp')->statement('DELETE FROM roles');
            DB::connection('nativephp')->statement('DELETE FROM permissions');

            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            Permission::create(['name' => 'user_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'user_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'user_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'user_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'role_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'role_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'role_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'role_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'permission_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'permission_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'permission_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'permission_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer_export', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer_import', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer-group_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer-group_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer-group_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'customer-group_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'product_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'product_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'product_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'product_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'product_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'product_import', 'guard_name' => 'web']);
            Permission::create(['name' => 'product_export', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'report_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'log_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'backup_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'setting_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'dashboard_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'category_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'category_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'category_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'category_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'category_import', 'guard_name' => 'web']);
            Permission::create(['name' => 'brand_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'brand_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'brand_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'brand_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'brand_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'brand_import', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'adjustment_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'adjustment_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'adjustment_edit', 'guard_name' => 'web']);
            Permission::create(['name' => 'adjustment_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'printer_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'printer_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'printer_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'printer_edit', 'guard_name' => 'web']);
            Permission::create(['name' => 'printer_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'quotation_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'quotation_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'quotation_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'quotation_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'quotation_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'quotation_sale', 'guard_name' => 'web']);
            Permission::create(['name' => 'send_quotation_mails', 'guard_name' => 'web']);
            Permission::create(['name' => 'print_barcodes', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_return_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_return_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_return_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_return_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_return_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_return_payments_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_return_payments_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_return_payments_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_return_payments_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_return_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_return_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_return_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_return_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_return_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_return_payments_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_return_payments_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_return_payments_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_return_payments_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'currency_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'currency_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'currency_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'currency_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_categories_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_categories_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_categories_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_categories_edit', 'guard_name' => 'web']);
            Permission::create(['name' => 'expense_categories_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_payment_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_payment_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_payment_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'purchase_payment_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'supplier_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'supplier_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'supplier_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'supplier_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'supplier_import', 'guard_name' => 'web']);
            Permission::create(['name' => 'supplier_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_payment_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_payment_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_payment_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'sale_payment_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'warehouse_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'warehouse_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'warehouse_show', 'guard_name' => 'web']);
            Permission::create(['name' => 'warehouse_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'warehouse_delete', 'guard_name' => 'web']);
            Permission::create(['name' => 'language_access', 'guard_name' => 'web']);
            Permission::create(['name' => 'language_create', 'guard_name' => 'web']);
            Permission::create(['name' => 'language_update', 'guard_name' => 'web']);
            Permission::create(['name' => 'language_delete', 'guard_name' => 'web']);

            $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            $role->syncPermissions(Permission::all());
        } catch (Throwable $e) {
            file_put_contents(storage_path('logs/debug_seeder.txt'), 'SEEDER ERROR: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e;
        }
    }
}
