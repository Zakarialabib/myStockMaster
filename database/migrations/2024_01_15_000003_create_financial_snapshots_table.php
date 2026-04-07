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
        Schema::create('financial_snapshots', function (Blueprint $blueprint): void {
            $blueprint->uuid('id')->primary();
            $blueprint->date('snapshot_date');
            $blueprint->string('period_type'); // 'daily', 'weekly', 'monthly', 'quarterly', 'yearly'

            // Revenue metrics
            $blueprint->decimal('total_revenue', 15, 2)->default(0);
            $blueprint->decimal('gross_revenue', 15, 2)->default(0);
            $blueprint->decimal('net_revenue', 15, 2)->default(0);
            $blueprint->integer('total_orders')->default(0);
            $blueprint->decimal('average_order_value', 10, 2)->default(0);

            // Cost metrics
            $blueprint->decimal('total_expenses', 15, 2)->default(0);
            $blueprint->decimal('cost_of_goods_sold', 15, 2)->default(0);
            $blueprint->decimal('operating_expenses', 15, 2)->default(0);

            // Profit metrics
            $blueprint->decimal('gross_profit', 15, 2)->default(0);
            $blueprint->decimal('net_profit', 15, 2)->default(0);
            $blueprint->decimal('profit_margin', 8, 4)->default(0); // Percentage
            $blueprint->decimal('gross_margin', 8, 4)->default(0); // Percentage

            // Break-even analysis
            $blueprint->decimal('break_even_point_units', 10, 2)->nullable();
            $blueprint->decimal('break_even_point_revenue', 15, 2)->nullable();
            $blueprint->integer('days_to_break_even')->nullable();

            // Growth metrics
            $blueprint->decimal('revenue_growth_rate', 8, 4)->nullable(); // Percentage
            $blueprint->decimal('profit_growth_rate', 8, 4)->nullable(); // Percentage

            // Additional metrics
            $blueprint->decimal('return_on_investment', 8, 4)->nullable(); // Percentage
            $blueprint->json('category_breakdown')->nullable(); // Revenue by category
            $blueprint->json('payment_method_breakdown')->nullable(); // Revenue by payment method
            $blueprint->json('top_products')->nullable(); // Top performing products

            $blueprint->json('metadata')->nullable(); // Additional calculated data
            $blueprint->timestamp('calculated_at');
            $blueprint->uuid('calculated_by')->nullable();
            $blueprint->timestamps();

            $blueprint->unique(['snapshot_date', 'period_type']);
            $blueprint->index(['period_type', 'snapshot_date']);
            $blueprint->index('calculated_at');
            $blueprint->index('calculated_by');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('financial_snapshots');
    }
};
