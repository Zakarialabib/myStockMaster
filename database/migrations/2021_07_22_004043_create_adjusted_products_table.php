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
        Schema::create('adjusted_products', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->foreignIdFor(Adjustment::class)->constrained()->cascadeOnDelete();
            $blueprint->foreignUuid('product_id')->nullable()->constrained()->cascadeOnDelete();
            $blueprint->foreignIdFor(Warehouse::class)->nullable()->constrained()->cascadeOnDelete();

            $blueprint->integer('quantity');
            $blueprint->string('type');
            $blueprint->softDeletes();
            $blueprint->timestamps();
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
