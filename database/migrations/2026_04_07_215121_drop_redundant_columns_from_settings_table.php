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
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'company_logo')) {
                $table->dropColumn('company_logo');
            }
            if (Schema::hasColumn('settings', 'notification_email')) {
                $table->dropColumn('notification_email');
            }
            if (Schema::hasColumn('settings', 'footer_text')) {
                $table->dropColumn('footer_text');
            }
            if (Schema::hasColumn('settings', 'is_invoice_footer')) {
                $table->dropColumn('is_invoice_footer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('company_logo')->nullable();
            $table->string('notification_email')->nullable();
            $table->text('footer_text')->nullable();
            $table->boolean('is_invoice_footer')->default(false);
        });
    }
};
