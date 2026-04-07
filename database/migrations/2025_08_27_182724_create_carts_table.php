<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('instance_name')->default('default');
            $blueprint->foreignIdFor(App\Models\User::class)->nullable()->constrained()->cascadeOnDelete();
            $blueprint->string('session_id')->nullable();
            $blueprint->json('conditions')->nullable();
            $blueprint->decimal('tax_rate', 8, 2)->default(0);
            $blueprint->timestamp('expires_at')->nullable();
            $blueprint->timestamps();

            $blueprint->index(['instance_name', 'user_id']);
            $blueprint->index(['instance_name', 'session_id']);
            $blueprint->index('expires_at');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
