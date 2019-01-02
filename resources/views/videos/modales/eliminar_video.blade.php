<div class="container-fluid" align="center">
    <h3><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;¿Confirmar eliminación del video?</h3>
</div>
<hr>
<table class="table">
    <tr>
        <td><b>ID interno:</b></td>
        <td>{{ $video->id }}</td>
    </tr>
    <tr>
        <td><b>Nombre del archivo original:</b></td>
        <td>{{ $video->nombre_archivo }}</td>
    </tr>
    <tr>
        <td><b>Titulo / descripcion:</b></td>
        <td>{{ $video->descripcion }}</td>
    </tr>
    <tr>
        <td><b>Fecha de carga:</b></td>
        <td>{{ $video->fecha_carga }}</td>
    </tr>
</table>
<hr>
<form action="{{ route('eliminar_video') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{ $video->id }}" readonly>

    <div class="form-group" align="right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <span class="separador">|</span>
        <input type="submit" class="btn btn-danger" value="Eliminar video">
    </div>
</form>