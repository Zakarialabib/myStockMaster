<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\PurchaseReturn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(PurchaseReturn::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->nullable()->constrained('products')->cascadeOnDelete();

            $table->string('name');
            $table->string('code');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->decimal('unit_price', 8, 2);
            $table->decimal('sub_total', 15, 2);
            $table->decimal('discount_amount', 15, 2);
            $table->string('discount_type')->default('fixed');
            $table->decimal('tax_amount', 15, 2);

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
        Schema::dropIfExists('purchase_return_details');
    }
}
