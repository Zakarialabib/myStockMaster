<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigInteger('user_id')->unsigned()->nullable()->index('wallets_user_id_foreign');
			$table->bigInteger('customer_id')->unsigned()->nullable()->index('wallets_customer_id_foreign');
			$table->bigInteger('supplier_id')->unsigned()->nullable()->index('wallets_supplier_id_foreign');
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