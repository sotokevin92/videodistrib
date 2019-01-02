<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcesoVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proceso_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_video');
            $table->string('tipo');
            $table->float('porcentaje', 5, 2)->default(0);
            $table->timestamps();

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
        Schema::dropIfExists('proceso_videos');
    }
}
