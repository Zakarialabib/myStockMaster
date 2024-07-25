<?php

declare(strict_types=1);

use App\Models\CashRegister;
use App\Models\Supplier;
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
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('reference');
            $table->foreignIdFor(Supplier::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Warehouse::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(CashRegister::class)->nullable()->constrained()->nullOnDelete();
            $table->integer('tax_percentage')->default(0);
            $table->decimal('tax_amount', 8, 2)->default(0);
            $table->integer('discount_percentage')->default(0);
            $table->decimal('discount_amount', 8, 2)->default(0);
            $table->decimal('shipping_amount', 8, 2)->default(0);
            $table->decimal('total_amount', 8, 2);
            $table->decimal('paid_amount', 8, 2);
            $table->decimal('due_amount', 8, 2);
            $table->string('status');
            $table->integer('payment_id')->nullable();
            $table->string('shipping_status')->nullable();
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
