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
            $table->uuid('id')->primary();
            $table->string('name', 192);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->string('city', 192)->nullable();
            $table->string('address')->nullable();
            $table->string('country', 192)->nullable();
            $table->unsignedInteger('role_id')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_all_warehouses')->default(false);
            $table->integer('default_client_id')->nullable();
            $table->integer('default_warehouse_id')->nullable();
            $table->foreignId('provider_id')->nullable();
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
