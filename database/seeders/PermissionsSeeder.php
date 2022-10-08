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
            'name' => 'user.access',
           ],
           [
            'id'    => 2,
            'name' => 'user.create',
           ],
              [
                'id'    => 3,
                'name' => 'user.update',
              ],
              [
                'id'    => 4,
                'name' => 'user.delete',
              ],
              [
                'id'    => 5,
                'name' => 'role.access',
              ],
              [
                'id'    => 6,
                'name' => 'role.create',
              ],
              [
                'id'    => 7,
                'name' => 'role.update',
              ],
              [
                'id'    => 8,
                'name' => 'role.delete',
              ],
              [
                'id'    => 9,
                'name' => 'permission.access',
              ],
              [
                'id'    => 10,
                'name' => 'permission.create',
              ],
              [
                'id'    => 11,
                'name' => 'permission.update',
              ],
              [
                'id'    => 12,
                'name' => 'permission.delete',
              ],
              [
                'id'   => 13,
                'name' => 'customer.access',
              ],
                [
                    'id'   => 14,
                    'name' => 'customer.create',
                ],
                [
                    'id'   => 15,
                    'name' => 'customer.update',
                ],
                [
                    'id'   => 16,
                    'name' => 'customer.delete',
                ],
                [
                    'id'   => 17,
                    'name' => 'product.access',
                ],
                [
                    'id'   => 18,
                    'name' => 'product.create',
                ],
                [
                    'id'   => 19,
                    'name' => 'product.update',
                ],
                [
                    'id'   => 20,
                    'name' => 'product.delete',
                ],
                [
                    'id'   => 21,
                    'name' => 'sale.access',
                ],
                [
                    'id'   => 22,
                    'name' => 'sale.create',
                ],
                [
                    'id'   => 23,
                    'name' => 'sale.update',
                ],
                [
                    'id'   => 24,
                    'name' => 'sale.delete',
                ],
                [
                    'id'   => 25,
                    'name' => 'purchase.access',
                ],
                [
                    'id'   => 26,
                    'name' => 'purchase.create',
                ],
                [
                    'id'   => 27,
                    'name' => 'purchase.update',
                ],
                [
                    'id'   => 28,
                    'name' => 'purchase.delete',
                ],
                [
                    'id'   => 29,
                    'name' => 'report.access',
                ],
                [
                    'id'   => 30,
                    'name' => 'report.create',
                ],
                [
                    'id'   => 31,
                    'name' => 'report.update',
                ],
                [
                    'id'   => 32,
                    'name' => 'report.delete',
                ],
                [
                    'id'   => 33,
                    'name' => 'setting.access',
                ],
                [
                    'id'   => 34,
                    'name' => 'dashboard.access',
                ],
                [
                    'id'   => 35,
                    'name' => 'category.access',
                ],
                [
                    'id'   => 36,
                    'name' => 'category.create',
                ],
                [
                    'id'   => 37,
                    'name' => 'category.update',
                ],
                [
                    'id'   => 38,
                    'name' => 'category.delete',
                ],
                [
                    'id'   => 39,
                    'name' => 'brand.access',
                ],
                [
                    'id'   => 40,
                    'name' => 'brand.create',
                ],
                [
                    'id'   => 41,
                    'name' => 'brand.update',
                ],
                [
                    'id'   => 42,
                    'name' => 'brand.delete',
                ],
                [
                    'id'   => 43,
                    'name' => 'expense.access',
                ],
                [
                    'id'   => 44,
                    'name' => 'expense.create',
                ],
                [
                    'id'   => 45,
                    'name' => 'expense.update',
                ],
                [
                    'id'   => 46,
                    'name' => 'expense.delete',
                ],
                [
                    'ud'   => 47,
                    'name' => 'supplier.access',
                ],
                [
                    'id'   => 48,
                    'name' => 'supplier.create',
                ],
                [
                    'id'   => 49,
                    'name' => 'supplier.update',
                ],
                [
                    'id'   => 50,
                    'name' => 'supplier.delete',
                ],
                [
                    'id'   => 51,
                    'name' => 'warehouse.access',
                ],
                [
                    'id'   => 52,
                    'name' => 'warehouse.create',
                ],
                [
                    'id'   => 53,
                    'name' => 'warehouse.update',
                ],
                [
                    'id'   => 54,
                    'name' => 'warehouse.delete',
                ],
                [
                    'id' => 55,
                    'name' => 'currency.access',
                ],
                [
                    'id' => 56,
                    'name' => 'currency.create',
                ],
                [
                    'id' => 57,
                    'name' => 'currency.update',
                ],
                [
                    'id' => 58,
                    'name' => 'currency.delete',
                ],
                [
                    'id' => 59,
                    'name' => 'transfer.access',
                ],
                [
                    'id' => 60,
                    'name' => 'transfer.create',
                ],
                [
                    'id' => 61,
                    'name' => 'transfer.update',
                ],
                [
                    'id' => 63,
                    'name' => 'transfer.delete',
                ],
                [
                    'id' => 64,
                    'name' => 'return.access',
                ],
                [
                    'id' => 65,
                    'name' => 'return.create',
                ],
                [
                    'id' => 66,
                    'name' => 'return.update',
                ],
                [
                    'id' => 67,
                    'name' => 'return.delete',
                ],
                
              

            
        ];

        Permission::insert($permissions);
    }
}
