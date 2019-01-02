<?php

namespace App;

use App\BaseModel;
use App\NowPlaying;
use App\Lista;

class Pantalla extends BaseModel
{
    protected $table = "pantallas";
    public $timestamps = false;

    public function lista() {
        return $this->hasOne('App\Lista', 'id', 'id_lista')->with('entries');
    }

    public function nowplaying() {
        return $this->hasOne('App\NowPlaying', 'id', 'id');
    }

    public function asignarLista($id_lista) {
        $this->id_lista = Lista::find($id_lista)->id or null;
        $this->save();

        $this->refresh();
    }

    public function clearLista() {
        $this->id_lista = null;
        $this->save();

        $this->refresh();
    }

    public static function getActivos() {
        return Pantalla::whereNotNull('id_lista');
    }
}
