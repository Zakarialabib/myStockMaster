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
        Schema::create('transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference');
            $table->integer('from_warehouse_id');
            $table->integer('to_warehouse_id');
            $table->integer('item');
            $table->integer('total_qty');
            $table->integer('total_tax');
            $table->decimal('total_cost', 8, 2);
            $table->decimal('total_amount', 8, 2);
            $table->double('shipping')->nullable();
            $table->string('document')->nullable();
            $table->integer('status');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('transfers');
    }
};
