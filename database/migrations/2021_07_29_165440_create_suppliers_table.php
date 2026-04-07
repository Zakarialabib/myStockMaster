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
        Schema::create('suppliers', function (Blueprint $blueprint): void {
            $blueprint->uuid('id')->primary();
            $blueprint->string('name');
            $blueprint->string('phone')->nullable();
            $blueprint->string('email')->nullable();
            $blueprint->string('city')->nullable();
            $blueprint->string('country')->nullable();
            $blueprint->text('address')->nullable();
            $blueprint->string('tax_number')->nullable();
            $blueprint->boolean('status')->default(1);
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
