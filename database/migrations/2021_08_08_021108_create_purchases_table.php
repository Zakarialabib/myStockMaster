<?php

declare(strict_types=1);

use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->date('date');
            $table->string('reference');

            $table->foreignIdFor(Supplier::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Warehouse::class)->nullable()->constrained()->cascadeOnDelete();
            $table->integer('tax_percentage')->default(0);
            $table->decimal('tax_amount',15,2)->default(0);
            $table->integer('discount_percentage')->default(0);
            $table->decimal('discount_amount',15,2)->default(0);
            $table->decimal('shipping_amount',15,2)->default(0);
            $table->decimal('total_amount',15,2);
            $table->decimal('paid_amount',15,2);
            $table->decimal('due_amount',15,2);
            $table->string('status');
            $table->string('payment_status');
            $table->string('payment_method');
            $table->string('document')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
