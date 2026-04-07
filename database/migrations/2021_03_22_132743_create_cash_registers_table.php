<?php

declare(strict_types=1);

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
        Schema::create('cash_registers', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(App\Models\User::class)->constrained()->cascadeOnDelete();
            $blueprint->foreignIdFor(Warehouse::class)->nullable()->constrained()->restrictOnDelete();
            $blueprint->decimal('cash_in_hand', 8, 2);
            $blueprint->decimal('recieved', 8, 2)->nullable();
            $blueprint->decimal('sent', 8, 2)->nullable();
            $blueprint->boolean('status');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
