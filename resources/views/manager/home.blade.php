@extends('layouts.base')

@section('titulo')
<i class="fa fa-television"></i>&nbsp;&nbsp;Dashboard - Now playing
@endsection

@section('contenido')
@foreach($nowplayings as $nowplaying)
@php
$pantalla = $nowplaying->pantalla
@endphp
<div class="col-md-6">
    <div class="panel panel-default">
		<div class="panel-heading">
			<b>({{ $pantalla->id }}) {{ $pantalla->descripcion }}</b> - <span id="titulo-{{ $pantalla->id }}">{{ $nowplaying->video->nombre }}</span>
		</div>
        <div class="panel-body" align="center">
            <video autoplay muted="muted" id="nowplaying-{{ $pantalla->id }}" src="{{ $nowplaying->video->proxy_url }}">
			</video>
			<script>
				url{{ $pantalla->id }} = "{{ route('nowplaying_ajax', [ $pantalla->id ]) }}";
				vid{{ $pantalla->id }} = document.getElementById("nowplaying-{{ $pantalla->id }}");

				setTimeout(() => {
					$.ajax({
						method: 'GET',
						url: url{{ $pantalla->id }},
						complete: function(r) {
							data = r.responseJSON;

							if (r.status == 200) {
								$(vid{{ $pantalla->id }}).attr('data-current', data.orden);
								playlist{{ $pantalla->id }} = data.entries;

								$(vid{{ $pantalla->id }}).on('ended', function() {
									sig = $(this).attr('data-current') + 1;
									if (sig > playlist{{ $pantalla->id }}.length) {
										sig = 1;
									}
									this.src = playlist{{ $pantalla->id }}[sig];
									$(this).attr('data-current', sig);
								});
							}
						}
					});
				}, {{ $nowplaying->remaining * 1000 }});

			</script>
        </div>
    </div>
</div>
@endforeach

@endsection
