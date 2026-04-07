<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\Category;
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
        Schema::create('products', function (Blueprint $blueprint): void {
            $blueprint->uuid('id')->primary();
            $blueprint->foreignIdFor(Category::class)->constrained()->restrictOnDelete();
            $blueprint->foreignIdFor(Brand::class)->nullable()->constrained()->nullOnDelete();

            $blueprint->string('name');
            $blueprint->string('code')->nullable();
            $blueprint->string('slug')->unique();
            $blueprint->string('barcode_symbology')->nullable();
            $blueprint->integer('quantity')->default(0);
            $blueprint->string('image')->nullable();
            $blueprint->string('gallery')->nullable();
            $blueprint->string('unit')->nullable();
            $blueprint->integer('tax_amount')->default(0);
            $blueprint->text('description')->nullable();
            $blueprint->boolean('status')->default(true);
            $blueprint->tinyInteger('tax_type')->default(0);
            $blueprint->text('embeded_video')->nullable();
            $blueprint->json('options')->nullable();
            $blueprint->text('usage')->nullable();
            $blueprint->boolean('featured')->default(false);
            $blueprint->boolean('best')->default(false);
            $blueprint->boolean('hot')->default(false);

            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
