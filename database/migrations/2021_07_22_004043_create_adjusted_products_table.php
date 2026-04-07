<?php

declare(strict_types=1);

use App\Models\Adjustment;
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
        Schema::create('adjusted_products', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Adjustment::class)->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Warehouse::class)->nullable()->constrained()->cascadeOnDelete();

            $table->integer('quantity');
            $table->string('type');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjusted_products');
    }
};
