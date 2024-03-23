<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type', 1); // C for CHECKS // P for Purchases
            $table->string('transaction_status', 1); // A for approved // P for Pending // D for Denied
            $table->unsignedBigInteger('wallet_id');
            $table->decimal('amount', 10, 2);
            $table->string('description', 155);
            $table->string('check_picture', 155)->nullable();
            $table->timestamps();
        });

        //FK
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('wallet_id')->references('id')->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
