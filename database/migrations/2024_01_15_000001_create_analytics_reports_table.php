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
        Schema::create('analytics_reports', function (Blueprint $blueprint): void {
            $blueprint->uuid('id')->primary();
            $blueprint->string('name');
            $blueprint->string('type'); // 'revenue', 'product_performance', 'sales_trend', etc.
            $blueprint->json('parameters'); // Store report parameters like date range, filters
            $blueprint->json('data'); // Store calculated report data
            $blueprint->json('metadata')->nullable(); // Additional metadata like chart configs
            $blueprint->string('status')->default('pending'); // 'pending', 'processing', 'completed', 'failed'
            $blueprint->timestamp('generated_at')->nullable();
            $blueprint->uuid('created_by')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();

            $blueprint->index(['type', 'created_at']);
            $blueprint->index(['status', 'created_at']);
            $blueprint->index('created_by');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('analytics_reports');
    }
};
