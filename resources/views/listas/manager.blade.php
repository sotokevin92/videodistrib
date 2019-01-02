@extends('layouts.base')

@section('titulo')
<i class="fa fa-list"></i>&nbsp;&nbsp;Listas de reproducción
@endsection

@section('contenido')
@include('videos.modales.videoplay')

<div class="modal fade" id="modal-confirmacion">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" style="padding-top: 2px;" class="close" data-dismiss="modal"><i class="fa fa-lg fa-times"></i></button>
                <h4 class="modal-title"><i class="fa fa-trash"></i>&nbsp;&nbsp;Confirmar eliminación</h4>
            </div>
            <div class="modal-body">
                <p>¿Proceder con la eliminación de la lista de reproducción?</p>
                <p>Esta acción no se puede deshacer. Detendrá la reproducción en los pantallas afectados, y no modifica los videos disponibles en el sistema.</p>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <form action="{{ route('eliminar_lista') }}" method="POST">
                        {{ csrf_field() }}
                        <input id="eliminar_id" name="lista" type="text" hidden readonly>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <span class="separador"> </span>
                        <input type="submit" class="btn btn-danger" value="Eliminar lista">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-asignacion_pantallas">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" style="padding-top: 2px;" class="close" data-dismiss="modal"><i class="fa fa-lg fa-times"></i></button>
                <h4 class="modal-title"><i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;Asignación de lista a pantallas</h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <a href="{{ route('lista_editar') }}" class="btn btn-default">
        <i class="fa fa-plus"></i>&nbsp;&nbsp;Nueva lista
    </a>
</div>
<hr>
<table class="table table-hover table-striped table-bordered" style="white-space: nowrap;">
    <thead class="centrado">
        <tr>
            <th rowspan="2" width="1%">ID</th>
            <th rowspan="2">Nombre</th>
            <th rowspan="2" width="1%">Formato</th>
            <th rowspan="2" width="1%">Cant. items</th>
            <th rowspan="2" width="1%">Duración total</th>
            <th colspan="2">Vigencia</th>
            <th rowspan="2"></th>
        </tr>
        <tr>
            <th width="10%">Desde</th>
            <th width="10%">Hasta</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listas as $lista)
            <tr>
                <td>
                    {{ $lista->id }}
                </td>
                <td>
                    {{ $lista->nombre }}
                </td>
                <td class="centrado">
                    @include('assets.formato_pantalla', ['retrato' => $lista->retrato])
                </td>
                <td class="aderecha">
                    {{ $lista->entries->count() }}
                </td>
                <td align="right">
                    {{ Carbon\Carbon::createFromTimestampUTC($lista->duracion)->format('i:s') }}
                </td>
                <td class="centrado">
                    {{ Carbon\Carbon::parse($lista->vigente_desde)->format('d/m/Y') }}
                </td>
                <td class="centrado">
                    {{ $lista->vigente_hasta ? Carbon\Carbon::parse($lista->vigente_hasta)->format('d/m/Y') : '' }}
                </td>
                <td class="centrado">
                    <div class="btn-group">
                        <a href="{{ route('lista_editar', ['lista' => $lista]) }}" class="btn btn-primary">Editar</a>
                        <a class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item asignacion_pantallas" data-id={{ $lista->id }} href="#" data-toggle="modal" data-target="#modal-asignacion_pantallas"><i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;Asignar a pantallas</a></li>
                            <li><a class="dropdown-item eliminar_lista" href="#" data-id="{{ $lista->id }}" data-toggle="modal" data-target="#modal-confirmacion"><b class="text-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;Eliminar</b></a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('scripts')
    <script src="{{ asset('js/lista_manager.js') }}"></script>
@endsection