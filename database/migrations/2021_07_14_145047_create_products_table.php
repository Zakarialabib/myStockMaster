<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->foreignIdFor(Category::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(Warehouse::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Brand::class)->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->string('barcode_symbology')->nullable();
            $table->integer('quantity');
            $table->decimal('cost',15,2);
            $table->decimal('price',15,2);
            $table->string('unit')->nullable();
            $table->integer('stock_alert');
            $table->integer('order_tax')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->default(1);
            $table->tinyInteger('tax_type')->nullable();
            $table->text('image')->nullable();
            $table->boolean('featured')->default(0);

            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
};
