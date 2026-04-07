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
        Schema::create('cache', function (Blueprint $blueprint): void {
            $blueprint->string('key')->primary();
            $blueprint->mediumText('value');
            $blueprint->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $blueprint): void {
            $blueprint->string('key')->primary();
            $blueprint->string('owner');
            $blueprint->integer('expiration');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
