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
        Schema::create('affiliate_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_id');
            $table->double('subscription_amount');
            $table->double('affilate_commision_amount');
            $table->integer('status')->default(1)->comment('1=active, 0=inactive, 9=delete');
            $table->timestamps();

            $table->index('affiliate_id');
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('payment_id');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliate_payments');
    }
};
