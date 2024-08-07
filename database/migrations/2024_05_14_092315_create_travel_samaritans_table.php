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
        Schema::create('travel_samaritans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('korean_name')->nullable();
            $table->string('spanish_name')->nullable();
            $table->string('portuguese_name')->nullable();
            $table->string('filipino_name')->nullable();
            $table->string('image')->nullable();
            $table->string('web_image')->nullable();
            $table->string('video')->nullable();
            $table->string('korean_video')->nullable();
            $table->string('spanish_video')->nullable();
            $table->string('portuguese_video')->nullable();
            $table->string('filipino_video')->nullable();
            $table->string('srt')->nullable();
            $table->string('korean_srt')->nullable();
            $table->string('spanish_srt')->nullable();
            $table->string('portuguese_srt')->nullable();
            $table->string('filipino_srt')->nullable();
            $table->integer('featured_video')->default(0);
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
        Schema::dropIfExists('travel_samaritans');
    }
};
