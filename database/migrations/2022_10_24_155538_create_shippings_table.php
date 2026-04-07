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
        Schema::create('shippings', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->boolean('is_pickup')->default(false);
            $blueprint->string('title');
            $blueprint->string('subtitle')->nullable();
            $blueprint->decimal('cost', 8, 2)->default(0);
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
        Schema::dropIfExists('shippings');
    }
};
