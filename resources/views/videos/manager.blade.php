@extends('layouts.base')

@section('titulo')
<i class="fa fa-play-circle"></i>&nbsp;&nbsp;Gestión de videos
@endsection

@section('contenido')

@include('videos.modales.videoplay')

<div class="modal fade" id="modal-gen">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="vertical-align: middle;">
                <button type="button" style="padding-top: 2px;" class="close" data-dismiss="modal"><i class="fa fa-lg fa-times"></i></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Cargar un nuevo video</strong>
            </div>
            <div class="panel-body" align="center">
                <form id="frm-subir_video" action="{{ route('subir_video') }}" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                    <div class="col-md-9">
                        <input class="form-control" type="file" accept="video/*" name="video_nuevo" id="carga-archivo" required>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" value="Subir video" id="btn-subir_video" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-striped table-hover" id="tabla-videos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Duracion</th>
                <th>Fecha de subida</th>
                <th>Vigencia</th>
                <th>Formato</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($videos as $video)
            <tr>
                <td>{{ $video->id }}</td>
                <td>
                    @if ($video->disponible)
                    <a href="#" data-toggle="modal" data-target="#modal-video" class="link_video" data-id="{{ $video->id }}">
                        <i class="fa fa-file-video-o"></i>&nbsp;{{ empty($video->descripcion) ? $video->nombre_archivo : $video->descripcion }}
                    </a>
                    @else
                    <a title="Video no disponible">
                        <i class="fa fa-clock-o"></i>&nbsp;{{ empty($video->descripcion) ? basename($video->nombre_archivo) : $video->descripcion }}
                    </a>
                    <div class="progress" style="height: 1em;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated progreso_video" data-id="{{ $video->id }}" style="width: {{ $video->proceso ? $video->proceso->porcentaje : 0 }}%"></div>
                    </div>
                    @endif
                </td>
                <td>{{ Carbon\Carbon::createFromTimestampUTC($video->duracion)->format('i:s') }}</td>
                <td>{{ $video->fecha_carga }}</td>
                <td>{{ $video->vigente_desde }} - {{ $video->vigente_hasta or '(indefinido)' }}</td>
                <td>
                    @include('assets.formato_pantalla', ['retrato' => $video->retrato])
                </td>
                <td>
                    @if ($video->disponible)
                    <div class="btn-group">
                        <a class="btn btn-primary editar_video" data-id="{{ $video->id }}" data-toggle="modal" data-target="#modal-gen">Editar</a>
                        <a class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu" style="x: -10px;">
                            <li><a class="dropdown-item eliminar_video" href="#" data-id="{{ $video->id }}" data-toggle="modal" data-target="#modal-gen"><b class="text-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;Eliminar</b></a></li>
                        </ul>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')

<script src="{{ asset('js/video_manager.js') }}"></script>
<script src="{{ asset('js/video_popup.js') }}"></script>

@endsection