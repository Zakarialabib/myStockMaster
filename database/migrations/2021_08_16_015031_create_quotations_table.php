<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\User;
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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->nullOnDelete();
            $table->foreignIdFor(User::class)->cascadeOnDelete();
            $table->foreignIdFor(Warehouse::class)->nullable()->cascadeOnDelete();
            $table->date('date');
            $table->string('reference');
            $table->integer('tax_percentage')->default(0);
            $table->integer('tax_amount')->default(0);
            $table->integer('discount_percentage')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->integer('shipping_amount')->default(0);
            $table->integer('total_amount');
            $table->string('status');
            $table->timestamp('sent_on')->nullable();
            $table->timestamp('expires_on')->nullable();
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
        Schema::dropIfExists('quotations');
    }
};
