@extends('layouts.base')

@section('titulo')
    <i class="fa fa-location-arrow"></i>&nbsp;&nbsp;Gestión de Pantallas
@endsection

@section('contenido')
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
    <a href="#" data-toggle="modal" data-target="#modal-gen" class="btn btn-default link_editar" data-id="-1"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nueva pantalla</a>
    <table class="table table-striped table-hover">
        <thead class="centrado">
            <th>ID</th>
            <th>Descripción</th>
            <th>Formato de pantalla</th>
            <th>Lista actual</th>
            <th>Habilitado</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($pantallas as $pantalla)
                <tr>
                    <td class="centrado">{{ $pantalla->id }}</td>
                    <td>{{ $pantalla->descripcion }}</td>
                    <td class="centrado">@include('assets.formato_pantalla', ['retrato' => boolval($pantalla->retrato)])</td>
                    <td>
                        <a style="cursor: pointer;" data-toggle="modal" data-target="#modal-gen" class="link_asignar" data-id="{{ $pantalla->id }}">
                        {{ $pantalla->lista->nombre or '(ninguna)' }}
                        </a>
                    </td>
                    <td class="centrado">
                        @include('assets.true_false', ['valor' => $pantalla->habilitado])
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary link_editar" data-id="{{ $pantalla->id }}" data-toggle="modal" data-target="#modal-gen"><i class="fa fa-edit"></i>&nbsp;&nbsp;Editar</a>
                            <a class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                                <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu" style="x: -10px;">
                                <li>
                                    <a class="dropdown-item link_habilitar" data-id={{ $pantalla->id }} style="cursor: pointer;">
                                        @if($pantalla->habilitado)
                                        <span class="text-danger"><i class="fa fa-hand-paper-o"></i>&nbsp;&nbsp;Deshabilitar</span>
                                        @else
                                        <span class="text-success"><i class="fa fa-thumbs-o-up"></i>&nbsp;&nbsp;Habilitar</span>
                                        @endif
                                    </a>
                                </li>
                                <li><a class="dropdown-item link_eliminar" href="#" data-id="{{ $pantalla->id }}" data-toggle="modal" data-target="#modal-gen"><b class="text-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;Eliminar</b></a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/pantalla_manager.js') }}"></script>
@endsection
