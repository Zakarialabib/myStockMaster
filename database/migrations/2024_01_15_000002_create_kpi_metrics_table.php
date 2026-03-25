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
        Schema::create('kpi_metrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('metric_name'); // 'revenue', 'profit_margin', 'roi', 'break_even_point', etc.
            $table->string('metric_type'); // 'revenue', 'profitability', 'efficiency', 'growth'
            $table->decimal('value', 15, 4); // The calculated KPI value
            $table->decimal('previous_value', 15, 4)->nullable(); // Previous period value for comparison
            $table->string('unit')->nullable(); // 'currency', 'percentage', 'days', 'units', etc.
            $table->date('period_start'); // Start date of the measurement period
            $table->date('period_end'); // End date of the measurement period
            $table->string('period_type'); // 'daily', 'weekly', 'monthly', 'quarterly', 'yearly'
            $table->json('calculation_data')->nullable(); // Store raw data used for calculation
            $table->json('metadata')->nullable(); // Additional context like filters applied
            $table->timestamp('calculated_at');
            $table->uuid('calculated_by')->nullable();
            $table->timestamps();

            $table->index(['metric_name', 'period_start', 'period_end']);
            $table->index(['metric_type', 'period_start']);
            $table->index(['period_type', 'calculated_at']);
            $table->index('calculated_by');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('kpi_metrics');
    }
};
