@extends('layouts.base_sidebar')

@section('sidebar_items')
<li>
    <a href="{{ route('manager') }}">
        Dashboard
    </a>
</li>
<li>
    <a href="{{ route('video_manager') }}">
        Videos
    </a>
</li>
<li>
    <a href="{{ route('lista_manager') }}">
        Listas de repr.
    </a>
</li>
<li>
    <a href="{{ route('lista_editar') }}">
        &emsp;Crear o editar lista
    </a>
</li>
<li>
    <a href="{{ route('pantalla_manager') }}">
        Pantallas
    </a>
</li>
@endsection
