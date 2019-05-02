<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pantalla;
use App\Lista;

class PantallaController extends Controller
{
    public function manager(Request $request) {
        $pantallas = Pantalla::all();

        return view('pantallas.manager', [ 'pantallas' => $pantallas ]);
    }

    public function editarPantalla(Request $request) {
        $pantalla = Pantalla::find($request->input('id'));

        $pantalla->descripcion = $request->input('descripcion');
        $pantalla->retrato = $request->input('retrato') === 'retrato';
        $pantalla->save();

        return redirect($request->header('referer'));
    }

    public function toggleHabilitacionPantalla(Request $request) {
        $pantalla = Pantalla::find($request->input('id'));

        $pantalla->habilitado = !$pantalla->habilitado;
        $pantalla->save();

        return redirect($request->header('referer'));
    }

    public function crearPantalla(Request $request) {
        $pantalla = new Pantalla();
        $pantalla->id = $request->input('id');
        $pantalla->descripcion = $request->input('descripcion');
        $pantalla->retrato = $request->input('retrato') === 'retrato';

        $pantalla->save();

        return redirect($request->header('referer'));
    }

    public function eliminarPantalla(Request $request) {
        $pantalla = Pantalla::find($request->input('id'));

        $pantalla->delete();

        return redirect($request->header('referer'));
    }

    public function modalEditarPantalla(Request $request) {
        $pantalla = Pantalla::find($request->input('id'));

        return view('pantallas.modales.editar', ['pantalla' => $pantalla]);
    }

    public function modalEliminarPantalla(Request $request) {
        $pantalla = Pantalla::find($request->input('id'));

        return view('pantallas.modales.eliminar', ['pantalla' => $pantalla]);
    }

    public function modalAsignarLista(Request $request) {
        $pantalla = Pantalla::find($request->input('id'));
        $listas = Lista::getVigentes();

        return view('pantallas.modales.asignar', ['pantalla' => $pantalla, 'listas' => $listas]);
    }

    public function asignarListasAPantallas(Request $request) {
        $pantalla = Pantalla::find($request->input('pantalla'));
        $lista = $request->input('lista');

        if ($lista !== null) {
            $pantalla->asignarLista($lista);
        } else {
            $pantalla->clearLista();
        }

        return redirect($request->header('referer'));
    }
}
