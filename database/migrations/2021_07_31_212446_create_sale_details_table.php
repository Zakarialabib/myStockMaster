<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Sale::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Warehouse::class)->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->decimal('unit_price', 8, 2);
            $table->decimal('sub_total', 15, 2);
            $table->decimal('product_discount_amount', 15, 2);
            $table->string('product_discount_type')->default('fixed');
            $table->integer('product_tax_amount');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_details');
    }
}
