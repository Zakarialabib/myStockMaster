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
        Schema::table('settings', function (Blueprint $blueprint): void {
            $blueprint->string('mail_mailer')->nullable();
            $blueprint->string('smtp_host')->nullable();
            $blueprint->string('smtp_port')->nullable();
            $blueprint->string('smtp_username')->nullable();
            $blueprint->string('smtp_password')->nullable();
            $blueprint->string('smtp_encryption')->nullable();
            $blueprint->string('mail_from_address')->nullable();
            $blueprint->string('mail_from_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $blueprint): void {
            $blueprint->dropColumn([
                'mail_mailer',
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'smtp_encryption',
                'mail_from_address',
                'mail_from_name',
            ]);
        });
    }
};
