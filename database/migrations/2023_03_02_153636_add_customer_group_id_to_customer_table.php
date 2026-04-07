<?php

declare(strict_types=1);

use App\Models\CustomerGroup;
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
        Schema::table('customers', function (Blueprint $blueprint): void {
            $blueprint->after('tax_number', function ($table) use ($blueprint): void {
                $blueprint->foreignIdFor(CustomerGroup::class)->nullable()->constrained()->cascadeOnDelete();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $blueprint): void {
            $blueprint->dropColumn('customer_group_id');
        });
    }
};
