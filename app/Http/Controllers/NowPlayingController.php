<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NowPlaying;
use App\ListaVideos;

class NowPlayingController extends Controller
{
    public function registrar(Request $request, $pantalla_id) {
        if (
            is_null($request->input('id_lista')) ||
            is_null($request->input('orden'))
        ) {
            return response("Request incompleta. ".json_encode([$pantalla_id, $request->input('id_lista'), $request->input('orden')]), 400);
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

	public function current(Request $request, $pantalla_id) {
		$n = NowPlaying::find($pantalla_id);

		if ($n === null)
			return response('', 404);

		if ($n->orden == 0) {
			return response('', 302);
		}

		$entries = $n->lista->with('entries')->first()->entries;
		$videos_orden = [];

		foreach ($entries as $entry) {
			$videos_orden[] = [
				'nombre' => $entry->video->nombre,
				'url' => $entry->video->proxy_url
			];
		}

		$entries_orden = array_combine(array_column($entries->toArray(), 'orden'), $videos_orden);

		return [
			'remaining' => $n->remaining,
			'orden' => $n->orden,
			'entries' => $entries_orden
		];
	}

}
