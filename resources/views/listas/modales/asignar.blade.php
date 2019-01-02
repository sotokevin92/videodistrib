
<form action="{{ route('asignar_lista') }}" method="POST">
    {{ csrf_field() }}
    <input type="text" name="lista" hidden readonly value="{{ $lista->id }}">

    <div class="container-fluid">
        <div class="form-group">
            <p>Seleccione los pantallas que pasarán a reproducir "<b>{{ $lista->nombre }}</b>". Mantenga presionada la tecla <b>Control</b> para marcar múltiples items.</p>
            <p>Si la pantalla seleccionado ya tiene una lista asignada, ésta tomará su lugar.</p>
            <label for="pantallas_target">Pantallas para asignar esta lista:</label>
            <select required multiple size=8 name="pantallas_target[]" id="select_pantallas" class="form-control">
                @foreach ($pantallas as $pantalla)
                    <option value="{{ $pantalla->id }}">{{ $pantalla->id }} {{ $pantalla->descripcion }}{{ $pantalla->id_lista ? " - [$pantalla->id_lista] ".$pantalla->lista->nombre : "" }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="container-fluid">
        <div class="form-group pull-right">
            <button data-dismiss="modal" type="button" class="btn btn-default">Cancelar</button>
            <span class="separador"> </span>
            <input type="submit" class="btn btn-primary" value="Asignar a pantallas seleccionados">
        </div>
    </div>
    <small>También puede <a href="{{ route('pantalla_manager') }}"><i class="fa fa-external-link"></i>&nbsp;&nbsp;Ir a Pantallas</a> para gestionar la lista y configuración general de cada uno.</small>
</form>
