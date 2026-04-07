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
        Schema::create('categories', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('code')->unique();
            $blueprint->string('name');
            $blueprint->string('description')->nullable();
            $blueprint->string('title')->nullable();
            $blueprint->string('image')->nullable();
            $blueprint->boolean('status')->default(true);
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
