<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(App\Models\Cart::class)->constrained()->cascadeOnDelete();
            $blueprint->string('associable_type')->nullable();
            $blueprint->unsignedBigInteger('associable_id')->nullable();
            $blueprint->string('name');
            $blueprint->decimal('price', 15, 2);
            $blueprint->integer('quantity');
            $blueprint->json('attributes')->nullable();
            $blueprint->json('conditions')->nullable();
            $blueprint->timestamps();

            $blueprint->index(['cart_id']);
            $blueprint->index(['associable_type', 'associable_id']);
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
