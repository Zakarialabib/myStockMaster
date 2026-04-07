<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('company_logo');
            $blueprint->string('company_name');
            $blueprint->string('company_email');
            $blueprint->string('company_phone');
            $blueprint->text('company_address');
            $blueprint->string('company_tax', 192)->nullable();
            $blueprint->string('telegram_channel', 192)->nullable();

            $blueprint->integer('default_currency_id');
            $blueprint->string('default_currency_position');
            $blueprint->string('default_date_format');

            $blueprint->integer('default_client_id')->nullable();
            $blueprint->integer('default_warehouse_id')->nullable();
            $blueprint->string('default_language', 192)->default('fr');
            $blueprint->boolean('is_rtl')->default(1);

            $blueprint->string('invoice_footer_text')->nullable();
            $blueprint->string('invoice_header')->nullable();
            $blueprint->string('invoice_footer')->nullable();

            $blueprint->string('sale_prefix', 25)->default('SA-');
            $blueprint->string('saleReturn_prefix', 25)->default('SRE-');
            $blueprint->string('purchase_prefix', 25)->default('PR-');
            $blueprint->string('purchaseReturn_prefix', 25)->default('PRE-');
            $blueprint->string('quotation_prefix', 25)->default('DE-');
            $blueprint->string('salePayment_prefix', 25)->default('SP-');
            $blueprint->string('purchasePayment_prefix', 25)->default('PR-');

            // $table->boolean('show_email')->default(1);
            // $table->boolean('show_address')->default(1);
            // $table->boolean('show_order_tax')->default(1);
            // $table->boolean('show_discount')->default(1);
            // $table->boolean('show_shipping')->default(1);

            $blueprint->boolean('backup_status')->default(0);
            $blueprint->string('backup_schedule')->nullable();

            $blueprint->json('invoice_control')->nullable();
            $blueprint->json('analytics_control')->nullable();

            $blueprint->enum('receipt_printer_type', ['browser', 'printer'])->default('browser');
            $blueprint->integer('printer_id')->nullable();

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
