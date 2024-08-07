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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('login_type')->nullable();
            $table->string('social_token')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('facebook_token')->nullable();
            $table->string('apple_token')->nullable();
            $table->string('password');
            $table->string('old_password')->nullable();
            $table->integer('is_login')->default(0);
            $table->rememberToken();
            $table->enum('gender',['male','female'])->default('male');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->integer('status')->default(1)->comment('1=active, 0=inactive, 9=delete');
            $table->string('country_code')->nullable();
            $table->string('mobile')->nullable();
            $table->unsignedBigInteger('affiliate_id')->nullable();
            $table->string('currency')->nullable();
            $table->string('language')->nullable();
            $table->timestamps();

            $table->index('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->index('affiliate_id');
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
