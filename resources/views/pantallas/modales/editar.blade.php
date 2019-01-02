@if(empty($pantalla))
<form action="{{ route('crear_pantalla') }}" method="POST">
@else
<form action="{{ route('editar_pantalla') }}" method="POST">
@endif
    {{ csrf_field() }}
    <div class="container-fluid">
        <div class="col-xs-4">
            <div class="form-group">
                <label for="id">ID de la pantalla:</label>
                <input name="id" class="form-control" type="text" pattern="^[1234][01][^0\D]$" required
                @if(!empty($pantalla))
                value="{{ $pantalla->id }}"
                readonly
                @endif
                >
            </div>
        </div>
        <div class="col-xs-8">
            <div class="form-group">
                <label for="descripcion">Descripción de la pantalla:</label>
                <input class="form-control" type="text" required name="descripcion"
                @if (!empty($pantalla))
                value="{{ $pantalla->descripcion }}"
                @endif
                >
            </div>
        </div>
    </div>
    <hr>
    <div class="container-fluid">
        <div class="col-xs-12">
            <div class="col-xs-8">
                <label for="select-orientacion">Orientación:</label>
                <select class="form-control" name="retrato" id="select-orientacion">
                    <option value="apaisado"
                    @if(!empty($pantalla))
                    {{ $pantalla->retrato ? '' : 'selected' }}
                    @endif
                    >
                        Apaisado
                    </option>
                    <option value="retrato"
                    @if(!empty($pantalla))
                    {{ $pantalla->retrato ? 'selected' : '' }}
                    @endif
                    >
                        Retrato
                    </option>
                </select>
            </div>
            <div class="col-xs-4" align="center">
                <img id="img-orientacion" src="{{ asset('img/formato-apaisado.png') }}">
            </div>
            <script>
                // Gracias a que jQuery se carga en la cabecera, puedo usarlo acá.
                // ¡SORPRENDENTE!
                $('#select-orientacion').on('change', function(e) {
                    url_imagen = window.location.origin + '/img/formato-' + $(this).val() + '.png';
                    $('#img-orientacion').attr('src', url_imagen);
                });
                $('#select-orientacion').trigger('change');
            </script>
        </div>
    </div>
    <hr>
    <div class="container-fluid">
        <div align="right">
            <button data-dismiss="modal" class="btn btn-default">Cancelar</button>
            <span class="separador"> </span>
            <input class="btn btn-primary" type="submit" value="Guardar pantalla">
        </div>
    </div>
</form>
