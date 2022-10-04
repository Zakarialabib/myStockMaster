<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->date('date');
            $table->string('reference', 192);
            $table->foreign('category_id')->references('id')->on('expense_categories')->restrictOnDelete();
			$table->integer('user_id')->index('expense_user_id');
			$table->integer('warehouse_id')->index('expense_warehouse_id');
			$table->string('details', 192);
			$table->float('amount', 10, 0);
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
        Schema::dropIfExists('expenses');
    }
}
