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
        Schema::table('products', function (Blueprint $blueprint): void {
            $blueprint->string('seasonality')->nullable();
            $blueprint->boolean('availability')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $blueprint): void {
            $blueprint->dropColumn('seasonality');
            $blueprint->dropColumn('availability');
        });
    }
};
