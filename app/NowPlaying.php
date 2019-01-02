<?php

namespace App;

use App\BaseModel;
use App\Lista;

class NowPlaying extends BaseModel
{
    protected $table = "now_playings";
    protected $fillable = [
        'id',
        'orden',
        'id_lista'
    ];

    public function lista() {
        return $this->hasOne('App\Lista', 'id', 'id_lista');
    }

    public function video() {
        return $this->lista->getEntry($this->orden);
    }

    public static function registrar($pantalla, $id_lista, $orden) {
        $registro = self::find($pantalla);

        if (empty($registro)) {
            $registro = new self();
            $registro->id = $pantalla;
        }

        $registro->id_lista = $id_lista;
        $registro->orden = $orden;

        $registro->save();

        $registro->refresh();

        return true;
    }
}
