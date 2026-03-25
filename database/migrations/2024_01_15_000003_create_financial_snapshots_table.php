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
        Schema::create('financial_snapshots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('snapshot_date');
            $table->string('period_type'); // 'daily', 'weekly', 'monthly', 'quarterly', 'yearly'

            // Revenue metrics
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->decimal('gross_revenue', 15, 2)->default(0);
            $table->decimal('net_revenue', 15, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('average_order_value', 10, 2)->default(0);

            // Cost metrics
            $table->decimal('total_expenses', 15, 2)->default(0);
            $table->decimal('cost_of_goods_sold', 15, 2)->default(0);
            $table->decimal('operating_expenses', 15, 2)->default(0);

            // Profit metrics
            $table->decimal('gross_profit', 15, 2)->default(0);
            $table->decimal('net_profit', 15, 2)->default(0);
            $table->decimal('profit_margin', 8, 4)->default(0); // Percentage
            $table->decimal('gross_margin', 8, 4)->default(0); // Percentage

            // Break-even analysis
            $table->decimal('break_even_point_units', 10, 2)->nullable();
            $table->decimal('break_even_point_revenue', 15, 2)->nullable();
            $table->integer('days_to_break_even')->nullable();

            // Growth metrics
            $table->decimal('revenue_growth_rate', 8, 4)->nullable(); // Percentage
            $table->decimal('profit_growth_rate', 8, 4)->nullable(); // Percentage

            // Additional metrics
            $table->decimal('return_on_investment', 8, 4)->nullable(); // Percentage
            $table->json('category_breakdown')->nullable(); // Revenue by category
            $table->json('payment_method_breakdown')->nullable(); // Revenue by payment method
            $table->json('top_products')->nullable(); // Top performing products

            $table->json('metadata')->nullable(); // Additional calculated data
            $table->timestamp('calculated_at');
            $table->uuid('calculated_by')->nullable();
            $table->timestamps();

            $table->unique(['snapshot_date', 'period_type']);
            $table->index(['period_type', 'snapshot_date']);
            $table->index('calculated_at');
            $table->index('calculated_by');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('financial_snapshots');
    }
};
