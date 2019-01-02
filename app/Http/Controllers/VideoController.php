<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Video;
use App\Jobs\ProcesarVideo;

class VideoController extends Controller
{
    public function manager() {
        return view('videos.manager', ['videos' => Video::all()]);
    }

    public function subirVideo(Request $request) {
        if (!$request->hasFile('video_nuevo')) {
            return redirect('/');
        }

        $nombre_archivo = $request->file('video_nuevo')->getClientOriginalName();

        $request->file('video_nuevo')->storeAs('uploads', $nombre_archivo);

        $nuevo_video = Video::crearVideo(storage_path("app/uploads/$nombre_archivo"));

        $job = new ProcesarVideo($nuevo_video->id);
        dispatch($job);

        return redirect('/manager/videos');
    }

    public function editarVideo(Request $request) {
        $video = Video::getById($request->input('id'));

        $video->descripcion = empty($request->input('descripcion')) ? '' : $request->input('descripcion');
        $video->vigente_desde = $request->input('vigente_desde');
        $video->vigente_hasta = $request->input('vigente_hasta');

        $video->save();

        return redirect('/manager/videos');
    }

    public function eliminarVideo(Request $request) {
        $video = Video::find($request->input('id'));

        $video->eliminarVideo();

        return redirect('/manager/videos');
    }

    public function modalEditarInfo(Request $request) {
        $vid = Video::find($request->input('id'));

        return view('videos.modales.editar_info', ['video' => $vid]);
    }

    public function modalEliminarVideo(Request $request) {
        $vid = Video::find($request->input('id'));

        return view('videos.modales.eliminar_video', ['video' => $vid]);
    }

    public function modalVideoPopup(Request $request) {
        $vid = Video::find($request->input('id'));

        return view('videos.modales.popup', ['video' => $vid]);
    }

    public function getProcesoVideo($id) {
        $vid = Video::find($id);

        return $vid->proceso ? $vid->proceso->porcentaje : 0;
    }
}
