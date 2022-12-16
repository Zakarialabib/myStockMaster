<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            $table->string('recieved_amount', 192)->nullable();
            $table->string('sent_amount', 192)->nullable();
            $table->string('balance', 192)->nullable();

            $table->foreignIdFor(User::class)->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Customer::class)->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(Supplier::class)->nullable()->constrained()->restrictOnDelete();

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
        Schema::dropIfExists('wallets');
    }
}
