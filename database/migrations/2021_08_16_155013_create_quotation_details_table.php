<?php

declare(strict_types=1);

use App\Models\Quotation;
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
        Schema::create('quotation_details', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->foreignIdFor(Quotation::class)->constrained()->cascadeOnDelete();
            $blueprint->foreignUuid('product_id')->nullable()->constrained('products')->cascadeOnDelete();

            $blueprint->string('name');
            $blueprint->string('code');
            $blueprint->integer('quantity');
            $blueprint->decimal('price', 8, 2);
            $blueprint->decimal('unit_price', 8, 2);
            $blueprint->decimal('sub_total', 15, 2);
            $blueprint->decimal('product_discount_amount', 15, 2);
            $blueprint->string('product_discount_type')->default('fixed');
            $blueprint->decimal('product_tax_amount', 15, 2);

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_details');
    }
};
