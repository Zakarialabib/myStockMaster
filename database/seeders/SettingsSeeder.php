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
                'show_email'                => '1',
                'show_address'              => '1',
                'show_order_tax'            => '1',
                'show_discount'             => '1',
                'show_shipping'             => '1',
                'created_at'                => now(),
            ],
        ];

        Setting::insert($settings);
    }
}
