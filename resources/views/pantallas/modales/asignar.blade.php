@if(!empty($listas))
<form action="{{ route('asignar_pantalla') }}" method="POST">
    {{ csrf_field() }}
    <input type="text" name="pantalla" hidden readonly value="{{ $pantalla->id }}">

    <div class="container-fluid">
        <div class="form-group">
            <p>Seleccione la lista a asignar a <b>{{ $pantalla->descripcion }}</b>.</p>
            <label for="select_lista">Listas disponibles:</label>
            <select required size=8 name="lista" id="select_lista" class="form-control">
                <option @if(!$pantalla->lista) selected @endif value="">(ninguna)</option>
                @foreach ($listas as $lista)
                    <option value="{{ $lista->id }}"
                        @if ($pantalla->lista && $pantalla->lista->id == $lista->id)
                        selected
                        @endif
                        >{{ $lista->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="container-fluid">
        <div class="form-group pull-right">
            <button data-dismiss="modal" type="button" class="btn btn-default">Cancelar</button>
            <span class="separador"> </span>
            <input type="submit" class="btn btn-primary" value="Asignar la lista seleccionada">
        </div>
    </div>
</form>
@else
<div class="container-fluid">
    <div class="form-group">
        <p>No hay listas disponibles para asignar.</p>
    </div>
    <hr>
</div>
@endif
    <small>También puede <a href="{{ route('lista_manager') }}"><i class="fa fa-external-link"></i>&nbsp;&nbsp;Ir a Listas de reproducción</a> para gestionar las listas y su contenido.</small>
