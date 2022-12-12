<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->string('barcode_symbology')->nullable();
            $table->integer('quantity');
            $table->integer('cost');
            $table->integer('price');
            $table->string('unit')->nullable();
            $table->integer('stock_alert');
            $table->integer('order_tax')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->tinyInteger('tax_type')->nullable();
            $table->text('image')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
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
