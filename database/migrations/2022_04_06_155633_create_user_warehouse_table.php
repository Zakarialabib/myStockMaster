<?php

declare(strict_types=1);

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
        Schema::create('user_warehouse', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(User::class)->index('user_warehouse_user_id')->constrained();
            $blueprint->foreignIdFor(Warehouse::class)->index('user_warehouse_warehouse_id')->constrained();
            $blueprint->boolean('is_default')->default(false);
            $blueprint->boolean('status')->default(true);
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('user_warehouse');
    }
};
