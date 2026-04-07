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
        Schema::create('email_templates', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->text('description')->nullable();
            $blueprint->text('message')->nullable();
            $blueprint->text('default')->nullable();
            $blueprint->json('placeholders')->nullable();
            $blueprint->string('type')->nullable();
            $blueprint->string('subject')->nullable();
            $blueprint->string('status')->default(true);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
