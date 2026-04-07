<?php

declare(strict_types=1);

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
        Schema::table('settings', function (Blueprint $blueprint): void {
            $blueprint->string('notification_email')->nullable();
            $blueprint->string('footer_text')->nullable();
            $blueprint->boolean('is_ecommerce_active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $blueprint): void {
            $blueprint->dropColumn(['notification_email', 'footer_text', 'is_ecommerce_active']);
        });
    }
};
