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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->string('name');
            $table->string('korean_name')->nullable();
            $table->string('spanish_name')->nullable();
            $table->string('portuguese_name')->nullable();
            $table->string('filipino_name')->nullable();
            $table->string('video_image')->nullable();
            $table->string('audio_image')->nullable();
            $table->text('description');
            $table->text('korean_description')->nullable();
            $table->text('spanish_description')->nullable();
            $table->text('portuguese_description')->nullable();
            $table->text('filipino_description')->nullable();
            $table->integer('count_id')->nullable();
            $table->string('video')->nullable();
            $table->string('korean_video')->nullable();
            $table->string('spanish_video')->nullable();
            $table->string('portuguese_video')->nullable();
            $table->string('filipino_video')->nullable();
            $table->string('audio')->nullable();
            $table->string('korean_audio')->nullable();
            $table->string('spanish_audio')->nullable();
            $table->string('portuguese_audio')->nullable();
            $table->string('filipino_audio')->nullable();
            $table->string('srt')->nullable();
            $table->string('korean_srt')->nullable();
            $table->string('spanish_srt')->nullable();
            $table->string('portuguese_srt')->nullable();
            $table->string('filipino_srt')->nullable();
            $table->timestamps();

            $table->index('book_id');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapters');
    }
};
