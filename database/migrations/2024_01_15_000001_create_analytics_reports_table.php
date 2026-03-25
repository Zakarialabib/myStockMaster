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
        Schema::create('analytics_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('type'); // 'revenue', 'product_performance', 'sales_trend', etc.
            $table->json('parameters'); // Store report parameters like date range, filters
            $table->json('data'); // Store calculated report data
            $table->json('metadata')->nullable(); // Additional metadata like chart configs
            $table->string('status')->default('pending'); // 'pending', 'processing', 'completed', 'failed'
            $table->timestamp('generated_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index('created_by');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('analytics_reports');
    }
};
