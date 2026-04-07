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
        Schema::create('product_warehouse', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->foreignIdFor(App\Models\Product::class)->constrained()->restrictOnDelete();
            $blueprint->foreignIdFor(App\Models\Warehouse::class)->constrained()->restrictOnDelete();

            $blueprint->decimal('price', 8, 2);
            $blueprint->decimal('cost', 8, 2)->nullable();
            $blueprint->decimal('old_price', 8, 2)->nullable();
            $blueprint->integer('qty');
            $blueprint->integer('stock_alert');
            $blueprint->boolean('is_ecommerce')->default(false);
            $blueprint->tinyInteger('is_discount')->default(false);
            $blueprint->date('discount_date')->nullable();

            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('product_warehouse');
    }
};
