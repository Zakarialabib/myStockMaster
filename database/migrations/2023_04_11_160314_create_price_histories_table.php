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
        Schema::create('price_histories', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignUuid('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $blueprint->foreignId('warehouse_id')->nullable()->constrained('warehouses')->cascadeOnDelete();
            $blueprint->integer('cost');
            $blueprint->date('effective_date')->nullable();
            $blueprint->date('expiry_date')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_histories');
    }
};
