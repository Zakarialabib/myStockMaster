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
        Schema::create('printers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->enum('connection_type', ['network', 'windows', 'linux']);
            $table->enum('capability_profile', ['default', 'simple', 'SP2000', 'TEP-200M', 'P822D'])->default('default');
            $table->string('char_per_line')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('port')->nullable();
            $table->string('path')->nullable();
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
        Schema::dropIfExists('printers');
    }
};
