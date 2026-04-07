<?php

declare(strict_types=1);

use App\Models\CashRegister;
use App\Models\ExpenseCategory;
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
        Schema::create('expenses', function (Blueprint $blueprint): void {
            $blueprint->id();

            $blueprint->foreignIdFor(ExpenseCategory::class, 'category_id')->constrained()->restrictOnDelete();
            $blueprint->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $blueprint->foreignIdFor(Warehouse::class)->nullable()->constrained()->restrictOnDelete();
            $blueprint->foreignIdFor(CashRegister::class)->nullable()->constrained()->nullOnDelete();
            $blueprint->date('date');
            $blueprint->string('reference', 192);
            $blueprint->string('description', 192)->nullable();
            $blueprint->decimal('amount', 8, 2);
            $blueprint->string('document')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
