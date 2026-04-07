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
        Schema::create('brands', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('name', 192);
            $blueprint->string('image')->nullable();
            $blueprint->text('description')->nullable();
            $blueprint->string('origin')->nullable();
            $blueprint->foreignId('category_id')->nullable();
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
        Schema::drop('brands');
    }
};
