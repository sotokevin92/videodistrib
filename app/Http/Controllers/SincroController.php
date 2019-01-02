<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pantalla;
use App\Video;

/**
 * Mayormente API para los clientes.
*/
class SincroController extends Controller
{
    /**
     * Lista de reproducción actual de la pantalla, id, nombres, hashes.
     *
     * Request:
     * { pantalla: id_pantalla }
     *
     */
    public function getLista(Request $request, $pantalla_id) {
        if (empty($pantalla_id)) {
            return response("Request incompleta.", 400);
        }
        try {
            $pantalla = Pantalla::find($pantalla_id);
            if ($pantalla == null || !$pantalla->habilitado) {
                throw new \Exception('Pantalla invalido o no habilitado.');
            }
            $lista_actual = $pantalla->lista;
            if ($lista_actual == null) {
                return response('No hay lista asignada.', 204);
            }
            return response(['lista' => $lista_actual], 200);
        }
        catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }


    /**
     * Ruta pública del archivo del id_video solicitado junto con su hash.
     *
     * Request:
     * { pantalla: id_pantalla, video: id_video }
     */
    public function getVideoDL(Request $request, $pantalla_id, $video_id) {
        $pantalla = Pantalla::find($pantalla_id);

        if (empty($pantalla)) {
            return response("Cliente no valido.", 400);
        }
        elseif (!$pantalla->habilitado) {
            return response("Cliente no habilitado.", 400);
        }

        if (empty($video_id)) {
            return response("Request incompleta.", 400);
        }

        $video = Video::find($video_id);

        if (empty($video)) {
            return response("Video no valido.", 400);
        }

        return response(
            [
                'id' => $video->id,
                'url' => $video->file_url,
                'hash' => $video->hash,
            ],
            200
        );
    }
}
