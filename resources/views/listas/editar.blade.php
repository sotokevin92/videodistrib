@extends('layouts.base')

@section('titulo')
@if(empty($lista))
Crear lista de reproducción
@else
Editar lista de reproducción [{{ $lista->id }}]
@endif
@endsection

@section('contenido')
<form action="{{ empty($lista) ? route('crear_lista') : route('guardar_info_lista') }}" id="form_datos_lista" method="POST">
    {{ csrf_field() }}
    @if(!empty($lista))
    @include('videos.modales.videoplay')
    <input type="text" hidden readonly name="lista" value="{{ $lista->id }}">
    @endif
    {{-- SECCIÓN DE DATOS BÁSICOS --}}
    <div class="row">
        <div class="container-fluid">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="nombre">Nombre de la lista *</label>
                    <input required autofocus class="form-control" type="text" name="nombre" value="{{ $lista->nombre or '' }}">
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="vigente_desde">Fecha de vigencia (comienzo)</label>
                    <input required class="form-control" type="date" name="vigente_desde" value="{{ $lista->vigente_desde or '' ? Carbon\Carbon::parse($lista->vigente_desde)->format('Y-m-d') : Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="vigente_hasta">Fecha de vigencia (fin)</label>
                    <input class="form-control" type="date" name="vigente_hasta" value="{{ $lista->vigente_hasta or '' ? Carbon\Carbon::parse($lista->vigente_hasta)->format('Y-m-d') : '' }}">
                </div>
            </div>
        </div>
    </div>

    @if(!empty($lista))
    <hr>
    {{-- SECCIÓN DE CONTENIDO --}}
    @if(!empty($videos))
    <div class="row">
        {{-- Primera columna --VIDEOS DISPONIBLES-- --}}
        <div class="col-xs-5">
            <h4>Videos disponibles</h4>
            <select multiple class="form-control" id="pool_videos" size=20>
                @foreach ($videos as $video)
                    <option title="{{ $video->nombre }}" value="{{ $video->id }}">
                        {{ $video->id }} - {{ $video->nombre }} &emsp;|&emsp; {{ $video->duracion_str }}
                    </option>
                @endforeach
            </select>
            @foreach ($videos as $video)
                <a hidden href="#" id="play_{{ $video->id }}" class="link_video" data-target="#modal-video" data-toggle="modal" data-id="{{ $video->id }}">_</a>
            @endforeach
        </div>

        {{-- Columna intermedia --CONTROLES DE VIDEO-LISTA-- --}}
        <div class="col-xs-1" style="padding-top: 8em;">
            <div style="margin-top: 10px; margin-bottom: 10px;">
                <button id="btn-agregar_video" disabled type="button" class="btn btn-default" title="Agregar video/s seleccionado/s"><i class="fa fa-fw fa-lg fa-arrow-right"></i></button>
            </div>
            <div style="margin-top: 10px; margin-bottom: 10px;">
                <button id="btn-quitar_video" disabled type="button" class="btn btn-warning" title="Quitar video/s seleccionado/s de la lista"><i class="fa fa-fw fa-lg fa-eraser"></i></button>
            </div>
        </div>

        {{-- Segunda columna --VIDEOS EN LISTA-- --}}
        <div class="col-xs-5">
            <h4>Videos en lista de reproducción</h4>
            <select form="form_datos_lista" class="form-control" name="items_lista[]" id="items_lista" size=20 multiple>
                @if(!empty($lista))
                @foreach($lista->entries as $entry)
                    <option title="{{ $entry->video->nombre }}" value="{{ $entry->video->id }}">
                        {{ $entry->video->id }} - {{ $entry->video->nombre }} &emsp;|&emsp; {{ $entry->video->duracion_str }}
                    </option>
                @endforeach
                @endif
            </select>
        </div>

        {{-- Columna final --CONTROLES DE LISTA-- --}}
        <div class="col-xs-1" style="padding-top: 8em;">
            <div style="margin-top: 10px; margin-bottom: 10px;">
                <button id="btn-desplazar_arriba" disabled type="button" class="btn btn-default" title="Desplazar video hacia arriba en la lista"><i class="fa fa-fw fa-lg fa-arrow-up"></i></button>
            </div>
            <div style="margin-top: 10px; margin-bottom: 10px;">
                <button id="btn-desplazar_abajo" disabled type="button" class="btn btn-default" title="Desplazar video hacia abajo en la lista"><i class="fa fa-fw fa-lg fa-arrow-down"></i></button>
            </div>
        </div>
    </div>
    @else
    <div class="container-fluid">
        <p><b>¡No hay videos disponibles para asignar!</b></p>
        <p>Ir a <a href="{{ route('video_manager') }}"><i class="fa fa-external-link"></i>&nbsp;&nbsp;Videos</a></p>
    </div>
    @endif
    @endif
    <hr>
    <div class="container-fluid">
        <div class="form-group">
            <div class="col-xs-6">
                <a class="btn btn-default" href="{{ route('lista_manager') }}"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Volver al listado</a>
            </div>
            <div class="col-xs-6" align="right">
                <input type="submit" class="btn btn-primary" value="Guardar lista">
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script src="{{ asset('js/editar_lista.js') }}"></script>
<script src="{{ asset('js/video_popup.js') }}"></script>
@endsection