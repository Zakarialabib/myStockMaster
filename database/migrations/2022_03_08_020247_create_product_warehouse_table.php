<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWarehouseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_warehouse', function(Blueprint $table)
		{
			$table->id();
			$table->unsignedBigInteger('product_id');
			$table->unsignedBigInteger('warehouse_id');
			$table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
			$table->foreign('warehouse_id')->references('id')->on('warehouses')->restrictOnDelete();
			$table->float('qty', 10, 0);
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

}
