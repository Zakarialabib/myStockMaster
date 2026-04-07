<?php

declare(strict_types=1);

use App\Models\PurchaseReturn;
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
        Schema::create('purchase_return_payments', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->foreignIdFor(PurchaseReturn::class)->constrained()->cascadeOnDelete();
            $blueprint->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $blueprint->integer('amount');
            $blueprint->date('date');
            $blueprint->string('reference');
            $blueprint->string('payment_method');
            $blueprint->text('note')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_payments');
    }
};
