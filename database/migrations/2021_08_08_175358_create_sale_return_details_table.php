<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_return_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name');
            $table->string('code');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('unit_price');
            $table->integer('sub_total');
            $table->integer('discount_amount');
            $table->string('discount_type')->default('fixed');
            $table->integer('tax_amount');
            $table->foreign('sale_return_id')->references('id')
                ->on('sale_returns')->cascadeOnDelete();
            $table->foreign('id')->references('id')
                ->on('products')->nullOnDelete();
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
        Schema::dropIfExists('sale_return_details');
    }
}
