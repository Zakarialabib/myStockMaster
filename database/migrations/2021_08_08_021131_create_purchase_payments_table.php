<?php

declare(strict_types=1);

use App\Models\Purchase;
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
        Schema::create('purchase_payments', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->foreignIdFor(Purchase::class)->constrained()->cascadeOnDelete();
            $blueprint->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('purchase_payments');
    }
};
