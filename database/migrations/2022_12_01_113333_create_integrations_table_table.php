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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->integer('type');
            $table->string('store_url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('sandbox')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('last_sync')->nullable();
            $table->string('products')->nullable();
            $table->string('orders')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('integrations');
    }
};
