<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lista;
use App\Video;
use App\ListaVideos;

use App\Pantalla;

class ListaController extends Controller
{
    public function manager(Request $request) {
        $listas = Lista::all();

        return view('listas.manager', ['listas' => $listas]);
    }

    public function vistaEditarLista(Request $request) {
        $videos = Video::getVigentes();
        $pantallas = Pantalla::all();

        $lista = Lista::find($request->input('lista'));
        return view('listas.editar', ['lista' => $lista, 'videos' => $videos, 'pantallas' => $pantallas]);
    }

    public function crearLista(Request $request) {
        $lista = new Lista();
        $lista->nombre = $request->input('nombre');
        $lista->vigente_desde = $request->input('vigente_desde');
        $lista->vigente_hasta = $request->input('vigente_hasta');
        $lista->save();

        return redirect(route('lista_editar', ['lista' => $lista]));
    }

    public function editarLista(Request $request) {
        $lista = Lista::find($request->input('lista'));
        $lista->nombre = $request->input('nombre');
        $lista->vigente_desde = $request->input('vigente_desde');
        $lista->vigente_hasta = $request->input('vigente_hasta');
        $lista->limpiar();
        $lista->save();

        $entries = $request->input('items_lista') ? $request->input('items_lista') : [];
        foreach($entries as $id_video) {
            $lista->agregarVideo($id_video);
        }

        return redirect(route('lista_manager'));
    }

    public function eliminarLista(Request $request) {
        $lista = Lista::find($request->input('lista'));
        $lista->delete();

        return redirect(route('lista_manager'));
    }

    public function modalAsignarLista(Request $request) {
        $lista = Lista::find($request->input('lista'));
        $pantallas = Pantalla::all();

        return view('listas.modales.asignar', [
            'lista' => $lista,
            'pantallas' => $pantallas,
        ]);
    }

    public function asignarLista(Request $request) {
        $lista = Lista::find($request->input('lista'));
        $pantallas = $request->input('pantallas_target');

        foreach($pantallas as $id_pantalla) {
            $pantalla = Pantalla::find($id_pantalla);
            $pantalla->id_lista = $lista ? $lista->id : $pantalla->id_lista;
            $pantalla->save();
        }

        return redirect(route('lista_manager'));
    }
}
