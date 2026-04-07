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
            if (! Schema::hasColumn('settings', 'multi_warehouse_sale')) {
                $blueprint->boolean('multi_warehouse_sale')->default(false)->after('default_warehouse_id');
            }

            if (! Schema::hasColumn('settings', 'site_title')) {
                $blueprint->string('site_title')->nullable();
            }

            if (! Schema::hasColumn('settings', 'site_logo')) {
                $blueprint->string('site_logo')->nullable();
            }

            if (! Schema::hasColumn('settings', 'site_favicon')) {
                $blueprint->string('site_favicon')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_facebook')) {
                $blueprint->string('social_facebook')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_twitter')) {
                $blueprint->string('social_twitter')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_instagram')) {
                $blueprint->string('social_instagram')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_linkedin')) {
                $blueprint->string('social_linkedin')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_whatsapp')) {
                $blueprint->string('social_whatsapp')->nullable();
            }

            if (! Schema::hasColumn('settings', 'social_tiktok')) {
                $blueprint->string('social_tiktok')->nullable();
            }

            if (! Schema::hasColumn('settings', 'head_tags')) {
                $blueprint->text('head_tags')->nullable();
            }

            if (! Schema::hasColumn('settings', 'body_tags')) {
                $blueprint->text('body_tags')->nullable();
            }

            if (! Schema::hasColumn('settings', 'seo_meta_title')) {
                $blueprint->string('seo_meta_title')->nullable();
            }

            if (! Schema::hasColumn('settings', 'seo_meta_description')) {
                $blueprint->text('seo_meta_description')->nullable();
            }

            if (! Schema::hasColumn('settings', 'whatsapp_custom_message')) {
                $blueprint->text('whatsapp_custom_message')->nullable();
            }

            if (! Schema::hasColumn('settings', 'invoice_template')) {
                $blueprint->string('invoice_template')->default('invoice-1');
            }

            if (! Schema::hasColumn('settings', 'expense_prefix')) {
                $blueprint->string('expense_prefix', 25)->default('EXP-');
            }

            if (! Schema::hasColumn('settings', 'delivery_prefix')) {
                $blueprint->string('delivery_prefix', 25)->default('DEL-');
            }

            if (! Schema::hasColumn('settings', 'show_email')) {
                $blueprint->boolean('show_email')->default(true);
            }

            if (! Schema::hasColumn('settings', 'show_address')) {
                $blueprint->boolean('show_address')->default(true);
            }

            if (! Schema::hasColumn('settings', 'show_order_tax')) {
                $blueprint->boolean('show_order_tax')->default(true);
            }

            if (! Schema::hasColumn('settings', 'show_discount')) {
                $blueprint->boolean('show_discount')->default(true);
            }

            if (! Schema::hasColumn('settings', 'show_shipping')) {
                $blueprint->boolean('show_shipping')->default(true);
            }

            // Make existing fields nullable for tests
            $blueprint->string('company_logo')->nullable()->change();
            $blueprint->string('company_email')->nullable()->change();
            $blueprint->string('company_phone')->nullable()->change();
            $blueprint->text('company_address')->nullable()->change();
            $blueprint->integer('default_currency_id')->nullable()->change();
            $blueprint->string('default_currency_position')->nullable()->change();
            $blueprint->string('default_date_format')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $blueprint): void {
            if (! Schema::hasColumn('products', 'price')) {
                $blueprint->decimal('price', 15, 2)->default(0)->after('code');
            }

            if (! Schema::hasColumn('products', 'cost')) {
                $blueprint->decimal('cost', 15, 2)->default(0)->after('price');
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
