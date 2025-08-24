<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /** Run the migrations. */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('seasonality')->nullable();
            $table->boolean('availability')->default(true);
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('seasonality');
            $table->dropColumn('availability');
        });
    }
};
