<div class="container-fluid" align="center">
    <h3><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;¿Confirmar eliminación de la pantalla?</h3>
</div>
<hr>
<table class="table">
    <tr>
        <td><b>ID:</b></td>
        <td>{{ $pantalla->id }}</td>
    </tr>
    <tr>
        <td><b>Descripción:</b></td>
        <td>{{ $pantalla->descripcion }}</td>
    </tr>
    <tr>
        <td><b>Lista actual:</b></td>
        <td>{{ $pantalla->lista->nombre or '(ninguna)' }}</td>
    </tr>
    <tr>
        <td><b>Formato de pantalla:</b></td>
        <td>@include('assets.formato_pantalla', ['retrato' => $pantalla->retrato])</td>
    </tr>
</table>
<hr>
<form action="{{ route('eliminar_pantalla') }}" method="post">
    {{ csrf_field() }}
    <input type="text" name="id" value="{{ $pantalla->id }}" hidden readonly>

    <div class="form-group" align="right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <span class="separador">|</span>
        <input type="submit" class="btn btn-danger" value="Eliminar pantalla">
    </div>
</form>