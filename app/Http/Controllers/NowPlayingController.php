<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NowPlaying;

class NowPlayingController extends Controller
{
    public function registrar(Request $request, $pantalla_id) {
        if (
            empty($request->input('id_lista')) ||
            empty($request->input('orden'))
        ) {
            return response("Request incompleta.", 400);
        }

        try {
            $resul = NowPlaying::registrar(
                $pantalla_id,
                is_string($request->input('id_lista')) ? intval($request->input('id_lista')) : $request->input('id_lista'),
                is_string($request->input('orden')) ? intval($request->input('orden')) : $request->input('orden')
            );
        }
        catch(\Exception $e) {
            return response($e->getMessage(), 500);
        }

        if (!$resul) {
            return response("No se pudo registrar punto de control.", 500);
        }

        return response("OK", 200);
    }

}
