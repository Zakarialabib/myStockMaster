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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->string('reference');
            $table->foreignIdFor(Supplier::class)->nullOnDelete();
            $table->foreignIdFor(User::class)->cascadeOnDelete();
            $table->foreignIdFor(Warehouse::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(CashRegister::class)->nullable()->cascadeOnDelete();
            $table->integer('tax_percentage')->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->integer('discount_percentage')->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2);
            $table->decimal('due_amount', 15, 2);
            $table->string('status');
            $table->integer('payment_id')->nullable();
            $table->text('note')->nullable();

            $table->softDeletes();
            $table->timestamps();
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
