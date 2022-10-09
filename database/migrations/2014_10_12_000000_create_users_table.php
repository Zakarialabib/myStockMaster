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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
			$table->string('name', 192);
			$table->string('email', 192);
			$table->string('password');
			$table->string('avatar')->nullable();
			$table->string('phone', 192);
			$table->integer('role_id');
			$table->boolean('statut')->default(1);
            $table->boolean('is_all_warehouses')->default(0);
            $table->integer('wallet_id')->nullable();
            $table->integer('default_client_id')->nullable();
            $table->integer('default_warehouse_id')->nullable();
			$table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
