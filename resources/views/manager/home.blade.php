@extends('layouts.base')

@section('titulo')
<i class="fa fa-television"></i>&nbsp;&nbsp;Inicio
@endsection

@section('contenido')

@include('modalgen')

@foreach($pantallas as $pantalla)

<div class="col-md-6">
	<div class="panel
	{{ $pantalla->habilitado ? "panel-success" : "panel-danger" }}
	">
		<div class="panel-heading">
			<b>({{ $pantalla->id }}) {{ $pantalla->descripcion }}</b> [ Formato de pantalla: @include('assets.formato_pantalla', ['retrato' => $pantalla->retrato]) ]
		</div>
        <div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					Habilitada para reproducción:<b>{{ $pantalla->habilitado ? "SÍ" : "NO" }}</b>
				</div>
				<div class="col-md-6">
					<a href="#" data-id={{ $pantalla->id }} class="btn btn-default btn-block link_habilitar">
						@if ($pantalla->habilitado)
							<i class="fa fa-stop"></i>&nbsp;&nbsp;Deshabilitar reproducción
						@else
							<i class="fa fa-play"></i>&nbsp;&nbsp;Habilitar reproducción
						@endif
					</a>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-6">
					Lista asignada:<br>&nbsp;&nbsp;
					<b>
					@if ($pantalla->lista)
					<a href="{{ route('lista_editar', [ 'lista' => $pantalla->lista->id ]) }}" title="Editar la lista de reproducción">{{ $pantalla->lista->nombre }}</a>
					@else
					(ninguna)
					@endif
					</b>
				</div>
				<div class="col-md-6">
						<a href="#" data-toggle="modal" data-target="#modal-gen" data-id={{ $pantalla->id }} class="btn btn-default btn-block link_asignar">Asignar nueva lista</a>
				</div>
			</div>
			<hr>
			<div class="col-md-12">
				<a href="#" data-toggle="modal" data-target="#modal-gen" data-id={{ $pantalla->id }} class="btn btn-default btn-block link_editar">Editar detalles de la pantalla</a>
			</div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
	<script src="{{ asset('js/pantalla_manager.js') }}"></script>
@endsection
