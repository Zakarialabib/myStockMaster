<?php

declare(strict_types=1);

use App\Models\CashRegister;
use App\Models\Supplier;
use App\Models\User;
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
        Schema::create('purchase_returns', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->date('date');
            $blueprint->string('reference');
            $blueprint->foreignIdFor(Supplier::class)->nullOnDelete();
            $blueprint->foreignIdFor(User::class)->cascadeOnDelete();
            $blueprint->foreignIdFor(Warehouse::class)->nullable()->constrained()->cascadeOnDelete();
            $blueprint->foreignIdFor(CashRegister::class)->nullable()->cascadeOnDelete();
            $blueprint->integer('tax_percentage')->default(0);
            $blueprint->decimal('tax_amount', 15, 2)->default(0);
            $blueprint->integer('discount_percentage')->default(0);
            $blueprint->decimal('discount_amount', 15, 2)->default(0);
            $blueprint->decimal('shipping_amount', 15, 2)->default(0);
            $blueprint->decimal('total_amount', 15, 2);
            $blueprint->decimal('paid_amount', 15, 2);
            $blueprint->decimal('due_amount', 15, 2);
            $blueprint->string('status');
            $blueprint->integer('payment_id')->nullable();
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
        Schema::dropIfExists('purchase_returns');
    }
};
