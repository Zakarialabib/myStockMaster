<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attributes', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $blueprint->enum('attribute', ['text', 'number', 'boolean', 'date']);
            $blueprint->text('value');
            $blueprint->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
