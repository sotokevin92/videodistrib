<div class="container-fluid">
    <form action="{{ route('guardar_info_video') }}" method="post">
        {{ csrf_field() }}
        <input type="text" name="id" readonly hidden value="{{ $video->id }}">
        <div class="form-group">
            <label for="descripcion">Titulo / descripcion:</label>
            <input placeholder="{{ $video->nombre_archivo }}" class="form-control" type="text" name="descripcion" value="{{ $video->descripcion }}">
        </div>
        <div class="form-group">
			<label for="vigente_desde">Fecha de vigencia (comienzo)</label>
			<input required class="form-control" type="datetime-local" name="vigente_desde" value="{{ $video->vigente_desde ? Carbon\Carbon::parse($video->vigente_desde)->format('Y-m-d\TH:i') : Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
		</div>
        <div class="form-group">
			<label for="vigente_hasta">Fecha de vigencia (fin)</label>
			<input class="form-control" type="datetime-local" name="vigente_hasta" value="{{ $video->vigente_hasta ? Carbon\Carbon::parse($video->vigente_hasta)->format('Y-m-d\TH:i') : '' }}">
		</div>
        <hr>
        <div class="form-group" align="right">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <span class="separador">|</span>
            <input type="submit" class="btn btn-primary" value="Guardar cambios">
        </div>
    </form>
</div>