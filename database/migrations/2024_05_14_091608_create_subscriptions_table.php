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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('plan');
            $table->integer('status')->default(1)->comment('1=active, 0=inactive, 9=delete');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('unsubscribe_request')->default(0);
            $table->string('feedback')->nullable();
            $table->integer('duration')->default(1);
            $table->text('receipt')->nullable();
            $table->text('purchase_token')->nullable();
            $table->string('app_id')->nullable();
            $table->string('product_id')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
