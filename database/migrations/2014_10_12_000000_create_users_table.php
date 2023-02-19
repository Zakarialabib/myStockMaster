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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name', 192);
            $table->string('email', 192);
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('phone', 192)->nullable();
            $table->integer('role_id')->default(1);
            $table->boolean('status')->default(1);
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
