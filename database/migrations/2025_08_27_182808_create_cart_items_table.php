<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->string('associable_type')->nullable();
            $table->unsignedBigInteger('associable_id')->nullable();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->json('attributes')->nullable();
            $table->json('conditions')->nullable();
            $table->timestamps();

            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->index(['cart_id']);
            $table->index(['associable_type', 'associable_id']);
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
