<?php

declare(strict_types=1);

use App\Models\Purchase;
use App\Models\Warehouse;
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
        Schema::create('purchase_details', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->foreignIdFor(Purchase::class)->constrained()->cascadeOnDelete();
            $blueprint->foreignUuid('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $blueprint->foreignIdFor(Warehouse::class)->nullable()->constrained()->nullOnDelete();

            $blueprint->string('name');
            $blueprint->string('code');
            $blueprint->decimal('quantity', 15, 2);
            $blueprint->decimal('price', 8, 2);
            $blueprint->decimal('unit_price', 8, 2);
            $blueprint->decimal('sub_total', 15, 2);
            $blueprint->decimal('product_discount_amount', 15, 2);
            $blueprint->string('product_discount_type')->default('fixed');
            $blueprint->integer('product_tax_amount')->default(0);

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
