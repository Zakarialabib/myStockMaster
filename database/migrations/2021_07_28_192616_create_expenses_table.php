<?php

declare(strict_types=1);

use App\Models\CashRegister;
use App\Models\ExpenseCategory;
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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(ExpenseCategory::class, 'category_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignIdFor(Warehouse::class)->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(CashRegister::class)->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->string('reference', 192);
            $table->string('description', 192)->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('document')->nullable();
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
        Schema::dropIfExists('expenses');
    }
};
