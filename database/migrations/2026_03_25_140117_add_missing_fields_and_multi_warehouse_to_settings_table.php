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
            if (! Schema::hasColumn('settings', 'multi_warehouse_sale')) {
                $table->boolean('multi_warehouse_sale')->default(false)->after('default_warehouse_id');
            }

            if (! Schema::hasColumn('settings', 'site_title')) {
                $table->string('site_title')->nullable();
            }

            if (! Schema::hasColumn('settings', 'site_logo')) {
                $table->string('site_logo')->nullable();
            }

            if (! Schema::hasColumn('settings', 'site_favicon')) {
                $table->string('site_favicon')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_facebook')) {
                $table->string('social_facebook')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_twitter')) {
                $table->string('social_twitter')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_instagram')) {
                $table->string('social_instagram')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_linkedin')) {
                $table->string('social_linkedin')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_whatsapp')) {
                $table->string('social_whatsapp')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_tiktok')) {
                $table->string('social_tiktok')->nullable();
            }

            if (! Schema::hasColumn('settings', 'head_tags')) {
                $table->text('head_tags')->nullable();
            }

            if (! Schema::hasColumn('settings', 'body_tags')) {
                $table->text('body_tags')->nullable();
            }

            if (! Schema::hasColumn('settings', 'seo_meta_title')) {
                $table->string('seo_meta_title')->nullable();
            }

            if (! Schema::hasColumn('settings', 'seo_meta_description')) {
                $table->text('seo_meta_description')->nullable();
            }

            if (! Schema::hasColumn('settings', 'whatsapp_custom_message')) {
                $table->text('whatsapp_custom_message')->nullable();
            }

            if (! Schema::hasColumn('settings', 'invoice_template')) {
                $table->string('invoice_template')->default('invoice-1');
            }

            if (! Schema::hasColumn('settings', 'expense_prefix')) {
                $table->string('expense_prefix', 25)->default('EXP-');
            }

            if (! Schema::hasColumn('settings', 'delivery_prefix')) {
                $table->string('delivery_prefix', 25)->default('DEL-');
            }

            if (! Schema::hasColumn('settings', 'show_email')) {
                $table->boolean('show_email')->default(true);
            }

            if (! Schema::hasColumn('settings', 'show_address')) {
                $table->boolean('show_address')->default(true);
            }

            if (! Schema::hasColumn('settings', 'show_order_tax')) {
                $table->boolean('show_order_tax')->default(true);
            }

            if (! Schema::hasColumn('settings', 'show_discount')) {
                $table->boolean('show_discount')->default(true);
            }

            if (! Schema::hasColumn('settings', 'show_shipping')) {
                $table->boolean('show_shipping')->default(true);
            }

            // Make existing fields nullable for tests
            $table->string('company_logo')->nullable()->change();
            $table->string('company_email')->nullable()->change();
            $table->string('company_phone')->nullable()->change();
            $table->text('company_address')->nullable()->change();
            $table->integer('default_currency_id')->nullable()->change();
            $table->string('default_currency_position')->nullable()->change();
            $table->string('default_date_format')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 15, 2)->default(0)->after('code');
            }

            if (! Schema::hasColumn('products', 'cost')) {
                $table->decimal('cost', 15, 2)->default(0)->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need for a complex rollback in this sandbox
    }
};
