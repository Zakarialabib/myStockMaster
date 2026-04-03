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
        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(PurchaseReturn::class)->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_id')->nullable()->constrained('products')->cascadeOnDelete();

            $table->string('name');
            $table->string('code');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->decimal('unit_price', 8, 2);
            $table->decimal('sub_total', 15, 2);
            $table->decimal('discount_amount', 15, 2);
            $table->string('discount_type')->default('fixed');
            $table->decimal('tax_amount', 15, 2);

            $table->timestamps();
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
