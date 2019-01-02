<?php

namespace App;

use App\BaseModel;
use Carbon\Carbon;

class ListaVideos extends BaseModel
{
    protected $table = "lista_videos";
    protected $hidden = [
        'id', 'id_lista', 'id_video'
    ];
    protected $fillable = [
        'orden',
        'id_lista',
        'id_video'
    ];
    public $timestamps = false;

    public function video() {
        return $this->hasOne('App\Video', 'id', 'id_video');
    }

    public function lista() {
        return $this->hasOne('App\Lista', 'id', 'id_lista');
    }

    public function getVigenteAttribute() {
        return $this->video->vigente;
    }
}
