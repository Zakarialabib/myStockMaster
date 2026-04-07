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
        Schema::create('kpi_metrics', function (Blueprint $blueprint): void {
            $blueprint->uuid('id')->primary();
            $blueprint->string('metric_name'); // 'revenue', 'profit_margin', 'roi', 'break_even_point', etc.
            $blueprint->string('metric_type'); // 'revenue', 'profitability', 'efficiency', 'growth'
            $blueprint->decimal('value', 15, 4); // The calculated KPI value
            $blueprint->decimal('previous_value', 15, 4)->nullable(); // Previous period value for comparison
            $blueprint->string('unit')->nullable(); // 'currency', 'percentage', 'days', 'units', etc.
            $blueprint->date('period_start'); // Start date of the measurement period
            $blueprint->date('period_end'); // End date of the measurement period
            $blueprint->string('period_type'); // 'daily', 'weekly', 'monthly', 'quarterly', 'yearly'
            $blueprint->json('calculation_data')->nullable(); // Store raw data used for calculation
            $blueprint->json('metadata')->nullable(); // Additional context like filters applied
            $blueprint->timestamp('calculated_at');
            $blueprint->uuid('calculated_by')->nullable();
            $blueprint->timestamps();

            $blueprint->index(['metric_name', 'period_start', 'period_end']);
            $blueprint->index(['metric_type', 'period_start']);
            $blueprint->index(['period_type', 'calculated_at']);
            $blueprint->index('calculated_by');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('kpi_metrics');
    }
};
