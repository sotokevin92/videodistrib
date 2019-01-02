<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

class CreateListaVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orden');
            $table->unsignedInteger('id_lista');
            $table->unsignedInteger('id_video');

            $table->foreign('id_lista')->references('id')->on('listas')->onDelete('cascade');
            $table->foreign('id_video')->references('id')->on('videos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lista_videos');
    }
}
