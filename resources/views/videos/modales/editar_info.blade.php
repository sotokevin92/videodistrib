<div class="container-fluid">
    <form action="{{ route('guardar_info_video') }}" method="post">
        {{ csrf_field() }}
        <input type="text" name="id" readonly hidden value="{{ $video->id }}">
        <div class="form-group">
            <label for="descripcion">Titulo / descripcion:</label>
            <input placeholder="{{ $video->nombre_archivo }}" class="form-control" type="text" name="descripcion" value="{{ $video->descripcion }}">
        </div>
        <div class="form-group">
            <label for="vigente_desde">Fecha-hora comienzo de vigencia:</label>
            <input placeholder="AAAA-MM-dd HH:mm:ss" class="form-control" type="text" name="vigente_desde" value="{{ $video->vigente_desde }}">
        </div>
        <div class="form-group">
            <label for="vigente_hasta">Fecha-hora fin de vigencia:</label>
            <input placeholder="AAAA-MM-dd HH:mm:ss" class="form-control" type="text" name="vigente_hasta" value="{{ $video->vigente_hasta }}">
        </div>
        <hr>
        <div class="form-group" align="right">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <span class="separador">|</span>
            <input type="submit" class="btn btn-primary" value="Guardar cambios">
        </div>
    </form>
</div>