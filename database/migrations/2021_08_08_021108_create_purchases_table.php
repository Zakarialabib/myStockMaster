<?php

declare(strict_types=1);

use App\Models\CashRegister;
use App\Models\Supplier;
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
        Schema::create('purchases', function (Blueprint $blueprint): void {
            $blueprint->uuid('id')->primary();
            $blueprint->date('date');
            $blueprint->string('reference');
            $blueprint->foreignIdFor(Supplier::class)->nullable()->constrained()->cascadeOnDelete();
            $blueprint->foreignUuid('user_id')->nullable()->constrained()->cascadeOnDelete();
            $blueprint->foreignIdFor(Warehouse::class)->nullable()->constrained()->cascadeOnDelete();
            $blueprint->foreignIdFor(CashRegister::class)->nullable()->constrained()->nullOnDelete();
            $blueprint->integer('tax_percentage')->default(0);
            $blueprint->decimal('tax_amount', 8, 2)->default(0);
            $blueprint->integer('discount_percentage')->default(0);
            $blueprint->decimal('discount_amount', 8, 2)->default(0);
            $blueprint->decimal('shipping_amount', 8, 2)->default(0);
            $blueprint->decimal('total_amount', 8, 2);
            $blueprint->decimal('paid_amount', 8, 2);
            $blueprint->decimal('due_amount', 8, 2);
            $blueprint->string('status');
            $blueprint->integer('payment_id')->nullable();
            $blueprint->string('shipping_status')->nullable();
            $blueprint->string('document')->nullable();
            $blueprint->text('note')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
