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
        Schema::create('warehouses', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('name', 192);
            $blueprint->string('city', 192)->nullable();
            $blueprint->text('address')->nullable();
            $blueprint->string('phone', 192)->nullable();
            $blueprint->string('email', 192)->nullable();
            $blueprint->string('country', 192)->nullable();
            $blueprint->boolean('status')->default(true);
            $blueprint->foreignId('user_id')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('warehouses');
    }
};
