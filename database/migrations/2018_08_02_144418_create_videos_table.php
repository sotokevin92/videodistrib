<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash')->nullable();
            $table->string('nombre_archivo');
            $table->string('descripcion');
            $table->timestamp('fecha_carga')->useCurrent();
            $table->timestamp('vigente_desde')->nullable();
            $table->timestamp('vigente_hasta')->nullable();
            $table->string('dimensiones');
            $table->unsignedInteger('duracion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
