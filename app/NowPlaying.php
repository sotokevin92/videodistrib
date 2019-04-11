<?php

namespace App;

use App\BaseModel;
use App\Lista;
use App\Pantalla;
use Carbon\Carbon;

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

    public function getEntryAttribute() {
        return $this->lista->getEntry($this->orden);
	}

	public function getVideoAttribute() {
        return $this->entry->video;
	}

	public function getPantallaAttribute() {
		return Pantalla::find($this->id);
	}

	public function getOffsetAttribute() {
		return Carbon::parse($this->updated_at)->diffInSeconds(Carbon::Now());
	}

	public function getRemainingAttribute() {
		return $this->video->duracion - $this->offset;
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

	public static function desregistrar($pantalla) {
		self::find($pantalla)->delete();

		return true;
	}
}
