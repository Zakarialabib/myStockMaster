<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('instance_name')->default('default');
            $table->uuid('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->json('conditions')->nullable();
            $table->decimal('tax_rate', 8, 2)->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['instance_name', 'user_id']);
            $table->index(['instance_name', 'session_id']);
            $table->index('expires_at');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
