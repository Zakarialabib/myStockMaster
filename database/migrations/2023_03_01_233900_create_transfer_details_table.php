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
        Schema::create('transfer_details', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->uuid('transfer_id');
            $blueprint->uuid('product_id');
            $blueprint->integer('warehouse_id');
            $blueprint->integer('quantity');

            $blueprint->foreign('transfer_id')
                ->references('id')
                ->on('transfers')
                ->cascadeOnDelete();

            $blueprint->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();

            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_details');
    }
};
