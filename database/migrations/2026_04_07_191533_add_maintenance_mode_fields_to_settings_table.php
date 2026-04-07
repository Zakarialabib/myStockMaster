<?php

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
        Schema::table('settings', function (Blueprint $table): void {
            $table->boolean('site_maintenance_status')->default(false);
            $table->string('site_maintenance_message')->nullable();
            $table->boolean('site_refresh')->default(false);
            $table->string('site_maintenance_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'site_maintenance_status',
                'site_maintenance_message',
                'site_refresh',
                'site_maintenance_secret',
            ]);
        });
    }
};
