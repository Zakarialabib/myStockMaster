<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->string('barcode_symbology')->nullable();
            $table->integer('quantity');
            $table->integer('cost');
            $table->integer('price');
            $table->string('unit')->nullable();
            $table->integer('stock_alert');
            $table->integer('order_tax')->nullable();
            $table->tinyInteger('tax_type')->nullable();
            $table->text('note')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
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
        Schema::dropIfExists('products');
    }
}
