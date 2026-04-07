<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $blueprint): void {
            $blueprint->date('start_date')->nullable();
            $blueprint->date('end_date')->nullable();
            $blueprint->enum('frequency', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->default('none');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $blueprint): void {
            $blueprint->dropColumn('start_date');
            $blueprint->dropColumn('end_date');
            $blueprint->dropColumn('frequency');
        });
    }
};
