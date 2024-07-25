<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'id'                        => 1,
                'company_name'              => 'Mystock',
                'company_email'             => 'contact@hotech.ma',
                'company_phone'             => '212 5 22 22 22 22',
                'company_logo'              => 'logo.png',
                'company_address'           => 'Rue 1, Casablanca, Maroc',
                'company_tax'               => '0',
                'telegram_channel'          => '',
                'default_currency_id'       => 1,
                'default_currency_position' => 'right',
                'default_date_format'       => 'd-m-Y',
                'default_client_id'         => '1',
                'default_warehouse_id'      => null,
                'default_language'          => 'fr',
                'invoice_header'            => '',
                'invoice_footer'            => '',
                'invoice_footer_text'       => 'Thank you for your business',
                'is_rtl'                    => '1',
                'sale_prefix'               => 'SA-000',
                'saleReturn_prefix'         => 'SRE-000',
                'purchase_prefix'           => 'PR-000',
                'purchaseReturn_prefix'     => 'PRE-000',
                'quotation_prefix'          => 'QU-000',
                'salePayment_prefix'        => 'SP-000',
                'purchasePayment_prefix'    => 'PP-000',
                'invoice_control'           => json_encode([
                    ['name' => 'show_email', 'status' => true],
                    ['name' => 'show_email', 'status' => true],
                    ['name' => 'show_address', 'status' => true],
                    ['name' => 'show_order_tax', 'status' => true],
                    ['name' => 'show_discount', 'status' => true],
                    ['name' => 'show_shipping', 'status' => true],
                ]),
                'analytics_control' => json_encode([
                    ['name' => 'total_categories', 'status' => true, 'color' => 'blue', 'position' => 1],
                    ['name' => 'total_products', 'status' => true, 'color' => 'orange', 'position' => 2],
                    ['name' => 'total_supplier', 'status' => true, 'color' => 'green', 'position' => 3],
                    ['name' => 'total_customer', 'status' => true, 'color' => 'indigo', 'position' => 4],
                    ['name' => 'sales', 'status' => true, 'color' => 'teal', 'position' => 5],
                    ['name' => 'purchases', 'status' => true, 'color' => 'cyan', 'position' => 6],
                    ['name' => 'best_selling_product', 'status' => false, 'color' => 'yellow', 'position' => 7],
                    ['name' => 'number_of_products_sold', 'status' => false, 'color' => 'purple', 'position' => 8],
                    ['name' => 'average_purchase_return_amount', 'status' => false, 'color' => 'red', 'position' => 9],
                    ['name' => 'common_return_reason', 'status' => false, 'color' => 'orange', 'position' => 10],
                    ['name' => 'average_payment_received_per_sale', 'status' => false, 'color' => 'green', 'position' => 11],
                    ['name' => 'significant_payment_changes', 'status' => false, 'color' => 'blue', 'position' => 12],
                ]),
                'created_at' => now(),
            ],
        ];

        Setting::insert($settings);
    }
}
