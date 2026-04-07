<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\User;
use App\Models\Warehouse;
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
        Schema::create('quotations', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignIdFor(Customer::class)->nullOnDelete();
            $blueprint->foreignIdFor(User::class)->cascadeOnDelete();
            $blueprint->foreignIdFor(Warehouse::class)->nullable()->cascadeOnDelete();
            $blueprint->date('date');
            $blueprint->string('reference');
            $blueprint->integer('tax_percentage')->default(0);
            $blueprint->integer('tax_amount')->default(0);
            $blueprint->integer('discount_percentage')->default(0);
            $blueprint->integer('discount_amount')->default(0);
            $blueprint->integer('shipping_amount')->default(0);
            $blueprint->integer('total_amount');
            $blueprint->string('status');
            $blueprint->timestamp('sent_on')->nullable();
            $blueprint->timestamp('expires_on')->nullable();
            $blueprint->text('note')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
