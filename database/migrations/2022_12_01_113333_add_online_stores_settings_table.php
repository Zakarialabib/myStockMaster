<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('woocommerce_store_url')->nullable();
            $table->integer('woocommerce_api_key')->nullable();
            $table->integer('woocommerce_api_secret')->nullable();
            $table->integer('shopify_store_url')->nullable();
            $table->integer('shopify_api_key')->nullable();
            $table->integer('shopify_api_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->removeColumn('woocommerce_store_url');
            $table->removeColumn('woocommerce_api_key');
            $table->removeColumn('woocommerce_api_secret');
            $table->removeColumn('shopify_store_url');
            $table->removeColumn('shopify_api_key');
            $table->removeColumn('shopify_api_secret');
        });
    }
};
