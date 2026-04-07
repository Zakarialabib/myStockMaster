<?php

declare(strict_types=1);

use App\Models\PurchaseReturn;
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
        Schema::create('purchase_return_details', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->foreignIdFor(PurchaseReturn::class)->constrained()->cascadeOnDelete();
            $blueprint->foreignUuid('product_id')->nullable()->constrained('products')->cascadeOnDelete();

            $blueprint->string('name');
            $blueprint->string('code');
            $blueprint->integer('quantity');
            $blueprint->decimal('price', 8, 2);
            $blueprint->decimal('unit_price', 8, 2);
            $blueprint->decimal('sub_total', 15, 2);
            $blueprint->decimal('discount_amount', 15, 2);
            $blueprint->string('discount_type')->default('fixed');
            $blueprint->decimal('tax_amount', 15, 2);

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_details');
    }
};
