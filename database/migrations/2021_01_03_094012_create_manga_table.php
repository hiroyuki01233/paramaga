<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->string('title', 100);
            $table->integer('published_flag');
            $table->integer('number_of_works');
            $table->string('url');
            $table->string('image_1');
            $table->string('image_2');
            $table->string('image_3');
            $table->string('image_4');
            $table->string('image_5');
            $table->string('image_6');
            $table->string('image_7');
            $table->string('image_8');
            $table->string('image_9');
            $table->string('image_10');
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
        Schema::dropIfExists('manga');
    }
}
