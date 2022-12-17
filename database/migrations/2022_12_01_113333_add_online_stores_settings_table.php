<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('woocommerce_store_url')->nullable();
            $table->string('woocommerce_api_key')->nullable();
            $table->string('woocommerce_api_secret')->nullable();
            $table->string('shopify_store_url')->nullable();
            $table->string('shopify_api_key')->nullable();
            $table->string('shopify_api_secret')->nullable();
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
            $table->dropColumn(['woocommerce_store_url', 'woocommerce_api_key', 'woocommerce_api_secret', 'shopify_store_url', 'shopify_api_key', 'shopify_api_secret']);
        });
    }
};
