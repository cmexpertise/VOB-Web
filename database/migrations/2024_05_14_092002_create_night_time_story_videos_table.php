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
        Schema::create('night_time_story_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('night_time_story_id');
            $table->string('name');
            $table->string('korean_name')->nullable();
            $table->string('spanish_name')->nullable();
            $table->string('portuguese_name')->nullable();
            $table->string('filipino_name')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('korean_description')->nullable();
            $table->text('spanish_description')->nullable();
            $table->text('portuguese_description')->nullable();
            $table->text('filipino_description')->nullable();
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
            $table->timestamps();

            $table->index('night_time_story_id');
            $table->foreign('night_time_story_id')->references('id')->on('night_time_stories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('night_time_story_videos');
    }
};
