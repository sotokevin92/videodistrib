@extends('layouts.base')

@section('titulo')
<i class="fa fa-television"></i>&nbsp;&nbsp;Dashboard - Now playing
@endsection

@section('contenido')
@foreach($pantallas as $pantalla)
@if($pantalla->getListaActual())
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">{{ $pantalla->descripcion }}</div>

        <div class="panel-body" align="center">
            @if($pantalla->retrato)
            <div style="width: 180px; height: 320px; background-color: blue;"></div>
            @else
            <div style="width: 320px; height: 180px; background-color: blue;"></div>
            @endif
        </div>
    </div>
</div>
@endif
@endforeach

@endsection
