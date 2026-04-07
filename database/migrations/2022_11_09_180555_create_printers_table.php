<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->string('name');
            $blueprint->enum('connection_type', ['network', 'windows', 'linux']);
            $blueprint->enum('capability_profile', ['default', 'simple', 'SP2000', 'TEP-200M', 'P822D'])->default('default');
            $blueprint->string('char_per_line')->nullable();
            $blueprint->string('ip_address')->nullable();
            $blueprint->string('port')->nullable();
            $blueprint->string('path')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
