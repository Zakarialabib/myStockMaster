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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 192);
            $table->string('city', 192)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 192)->nullable();
            $table->string('email', 192)->nullable();
            $table->string('country', 192)->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('user_id')->nullable();
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
        Schema::drop('warehouses');
    }
};
