<?php

namespace App;

use App\BaseModel;

use Carbon\Carbon;

use App\Video;
use App\ListaVideos;

class Lista extends BaseModel
{
    protected $table = "listas";
    protected $fillable = [
        'nombre',
        'id_pantalla',
        'prioridad',
        'vigente_desde',
        'vigente_hasta'
    ];

    protected $hidden =[
        'videos',
    ];

    public $timestamps = false;

    public function entries() {
        return $this->hasMany('App\ListaVideos', 'id_lista', 'id')->with('video');
    }

    public function pantalla() {
        return $this->hasOne('App\Pantalla', 'id', 'id_pantalla');
    }

    /**
     * Devolver verdadero si la lista está vigente ahora mismo
     */
    public function getVigenteAttribute() {
        return ((!$this->vigente_desde && !$this->vigente_hasta) ||
            ($this->vigente_desde <= Carbon::now() && !$this->vigente_hasta) ||
            ($this->vigente_desde <= Carbon::now() && Carbon::now() <= $this->vigente_hasta)
            && (!$this->vacia)
        );
    }

    /**
     * Devuelve los videos de la lista (distinct id_video de los entries)
     */
    public function getVideosAttribute() {
        $videos = [];
        foreach($this->entries()->getQuery()->distinct('id_video')->get() as $entry) {
            $videos[] = $entry->video;
        }
        return $videos;
    }

    /**
     * Devuelve verdadero si todos los videos de la lista son retrato,
     * falso si todos los videos de la lista son apaisados o
     * null si hay mezcla de formatos.
     */
    public function getRetratoAttribute() {
        $formato = null;

        foreach($this->videos as $vid) {
            if ($formato === null) {
                $formato = $vid->retrato;
                continue;
            }

            if ($formato != $vid->retrato) {
                $formato = null;
                break;
            }
        }

        return $formato;
    }

    /**
     * Devolver verdadero si la lista no tiene contenido para reproducir
     */
    public function getVaciaAttribute() {
        return $this->duracion == 0;
    }

    /**
     * Devuelve la entrada de la lista de la posición solicitada
     */
    public function getEntry($posicion) {
        return ListaVideos::with('video')->whereIdLista($this->id)->whereOrden($posicion)->first();
    }

    /**
     * Devolver la cantidad de elementos en la lista
     */
    public function getCantElementosAttribute() {
        return ListaVideos::whereIdLista($this->id)->count();
    }

    /**
     * Devolver la duración total de la lista en segundos
     */
    public function getDuracionAttribute() {
        $entries = ListaVideos::whereIdLista($this->id)->get();

        $duracion = 0;

        foreach($entries as $entry) {
            if ($entry->vigente) {
                $duracion += $entry->video->duracion;
            }
        }

        return $duracion;
    }

    /**
     * Agregar un video a la lista en la posicion elegida (base 1)
     */
    public function agregarVideo($id_video, $posicion = 0) {
        $entry = new ListaVideos();
        $entry->id_lista = $this->id;
        $entry->id_video = $id_video;

        if ($posicion == 0) {
            // Sin especificar, va al final
            $entry->orden = $this->cant_elementos + 1;
        } else {
            // Todas las entradas que estén a partir de esa posición se desplazan hacia atrás
            $entries = ListaVideos::whereIdLista($this->id)->where('orden', '>=', $posicion)->get();
            foreach($entries as $entry_e) {
                $entry_e->orden += 1;
                $entry_e->save();
            }

            $entry->orden = $posicion;
        }

        $entry->save();

        $this->ordenar();
    }

    /**
     * Colocar números consecutivos en los elementos de la lista
     */
    public function ordenar($posicion_desde = 1) {
        $entries = ListaVideos::whereIdLista($this->id)->where('orden', '>=', $posicion_desde)->orderBy('orden')->get();

        $indice = $posicion_desde;
        foreach($entries as $entry) {
            $entry->orden = $indice++;
            $entry->save();
        }

        $this->refresh();
    }

    /**
     * Borrar el video de TAL posición
     */
    public function eliminarVideo($posicion) {
        // Borrar al carajo
        $entry = ListaVideos::whereIdLista($this->id)->whereOrden($posicion)->delete();

        // Ordenar
        $this->ordenar($posicion);
    }

    public function limpiar() {
        // Borrar TODO al carajo
        ListaVideos::whereIdLista($this->id)->delete();

        $this->refresh();
    }

    public static function getVigentes() {
        $ret = [];
        foreach(Lista::all() as $lista) {
            if ($lista->vigente) {
                $ret[] = $lista;
            }
        }

        return $ret;
    }
}
