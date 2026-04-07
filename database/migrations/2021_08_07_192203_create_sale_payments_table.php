<?php

declare(strict_types=1);

use App\Models\CashRegister;
use App\Models\Sale;
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
        Schema::create('sale_payments', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(Sale::class)->nullable()->constrained('sales')->cascadeOnDelete();
            $blueprint->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $blueprint->foreignIdFor(CashRegister::class)->nullable()->cascadeOnDelete();
            $blueprint->decimal('amount', 8, 2);
            $blueprint->date('date');
            $blueprint->string('reference');
            $blueprint->string('payment_method');
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
        Schema::dropIfExists('sale_payments');
    }
};
