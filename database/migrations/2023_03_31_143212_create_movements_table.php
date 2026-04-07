<?php

declare(strict_types=1);

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
        Schema::create('movements', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('type');
            $blueprint->unsignedInteger('quantity');
            $blueprint->decimal('price', 10, 2);
            $blueprint->dateTime('date');
            $blueprint->uuidMorphs('movable');
            $blueprint->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
