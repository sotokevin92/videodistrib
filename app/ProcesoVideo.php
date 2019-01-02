<?php

namespace App;

use App\BaseModel;
use App\Video;

class ProcesoVideo extends BaseModel
{
    protected $table = "proceso_videos";
    protected $fillable = [
        'id_video',
        'tipo',
        'porcentaje'
    ];

    public function video() {
        return $this->hasOne('App\Video', 'id', 'id_video');
    }

    public static function getProceso($id_video) {
        $query = self::where('id_video', $id_video)->first();

        return $query;
    }

    public static function generarProceso($id_video, $tipo) {
        if (empty(Video::find($id_video))) {
            return null;
        }

        $proceso = ProcesoVideo::whereIdVideo($id_video)->whereTipo($tipo)->delete();
        
        $proceso = new ProcesoVideo();
        $proceso->id_video = $id_video;
        $proceso->tipo = $tipo;
        $proceso->save();

        return $proceso;
    }

    public function setPorcentaje($porcentaje) {
        $this->porcentaje = $porcentaje;
        try {
            $this->save();
        }
        catch(\Exception $e) {
            return false;
        }
    }
}
