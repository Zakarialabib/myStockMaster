<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adjustments', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->date('date');
            $blueprint->string('reference');
            $blueprint->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $blueprint->foreignIdFor(Warehouse::class)->nullable()->constrained()->nullOnDelete();
            $blueprint->text('note')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adjustments');
    }
};
