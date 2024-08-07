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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('korean_name')->nullable();
            $table->string('spanish_name')->nullable();
            $table->string('portuguese_name')->nullable();
            $table->string('filipino_name')->nullable();
            $table->string('video_image')->nullable();
            $table->string('audio_image')->nullable();
            $table->integer('type')->default(1)->comment('1 = new testament,2 = old testament,3 = four gospets');
            $table->text('description');
            $table->text('korean_description')->nullable();
            $table->text('spanish_description')->nullable();
            $table->text('portuguese_description')->nullable();
            $table->text('filipino_description')->nullable();
            $table->integer('count_id')->nullable();
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
        Schema::dropIfExists('books');
    }
};
