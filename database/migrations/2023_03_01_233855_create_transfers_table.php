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
        Schema::create('transfers', function (Blueprint $blueprint): void {
            $blueprint->uuid('id')->primary();
            $blueprint->string('reference');
            $blueprint->integer('from_warehouse_id');
            $blueprint->integer('to_warehouse_id');
            $blueprint->integer('item');
            $blueprint->integer('total_qty');
            $blueprint->integer('total_tax');
            $blueprint->decimal('total_cost', 8, 2);
            $blueprint->decimal('total_amount', 8, 2);
            $blueprint->double('shipping')->nullable();
            $blueprint->string('document')->nullable();
            $blueprint->integer('status');
            $blueprint->text('note')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
