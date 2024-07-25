<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_logo');
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone');
            $table->text('company_address');
            $table->string('company_tax', 192)->nullable();
            $table->string('telegram_channel', 192)->nullable();

            $table->integer('default_currency_id');
            $table->string('default_currency_position');
            $table->string('default_date_format');

            $table->integer('default_client_id')->nullable();
            $table->integer('default_warehouse_id')->nullable();
            $table->string('default_language', 192)->default('fr');
            $table->boolean('is_rtl')->default(1);

            $table->string('invoice_footer_text')->nullable();
            $table->string('invoice_header')->nullable();
            $table->string('invoice_footer')->nullable();

            $table->string('sale_prefix', 25)->default('SA-');
            $table->string('saleReturn_prefix', 25)->default('SRE-');
            $table->string('purchase_prefix', 25)->default('PR-');
            $table->string('purchaseReturn_prefix', 25)->default('PRE-');
            $table->string('quotation_prefix', 25)->default('DE-');
            $table->string('salePayment_prefix', 25)->default('SP-');
            $table->string('purchasePayment_prefix', 25)->default('PR-');

            $table->boolean('show_email')->default(1);
            $table->boolean('show_address')->default(1);
            $table->boolean('show_order_tax')->default(1);
            $table->boolean('show_discount')->default(1);
            $table->boolean('show_shipping')->default(1);

            $table->boolean('backup_status')->default(0);
            $table->string('backup_schedule')->nullable();

            $table->enum('receipt_printer_type', ['browser', 'printer'])->default('browser');
            $table->integer('printer_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
