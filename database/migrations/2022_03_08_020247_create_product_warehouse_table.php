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
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id();

            $table->uuid('product_id');
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();

            $table->decimal('price', 8, 2);
            $table->decimal('cost', 8, 2)->nullable();
            $table->decimal('old_price', 8, 2)->nullable();
            $table->integer('qty');
            $table->integer('stock_alert');
            $table->boolean('is_ecommerce')->default(false);
            $table->tinyInteger('is_discount')->default(false);
            $table->date('discount_date')->nullable();

            $table->softDeletes();
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
        Schema::drop('product_warehouse');
    }
};
