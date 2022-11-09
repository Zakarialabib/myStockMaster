<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('site_logo')->nullable();
            $table->integer('default_currency_id');
            $table->string('default_currency_position');
            $table->string('notification_email');
            $table->text('footer_text');
            $table->text('company_address');
            $table->integer('default_client_id')->nullable();
            $table->integer('default_warehouse_id')->nullable();
            $table->string('default_language', 192)->default('fr');
            $table->boolean('is_invoice_footer')->default(0);
            $table->string('invoice_footer', 192)->nullable();
            $table->string('company_tax', 192)->nullable();
            
            $table->boolean('is_rtl')->default(1);
            $table->string('invoice_prefix', 192)->default('INV-');

            $table->boolean('show_email')->default(1);
            $table->boolean('show_address')->default(1);

            $table->boolean('show_order_tax')->default(1);
            $table->boolean('show_discount')->default(1);
            $table->boolean('show_shipping')->default(1);
            
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
