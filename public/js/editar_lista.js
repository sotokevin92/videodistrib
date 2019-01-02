$(function() {
    $('#form_datos_lista').on('submit', function(e) {
        $('#items_lista option').prop('selected', true);
        return true;
    });

    $('#pool_videos').on('change', function(e) {
        $('#btn-agregar_video').attr('disabled', this.selectedOptions.length == 0);
    });

    $('#items_lista').on('change', function(e) {
        $('#btn-desplazar_arriba').attr('disabled', this.selectedOptions.length != 1);
        $('#btn-desplazar_abajo').attr('disabled', this.selectedOptions.length != 1);
        
        $('#btn-quitar_video').attr('disabled', this.selectedOptions.length == 0);
    });

    $('#btn-agregar_video').on('click', function(e) {
        indice_insertar = $('#items_lista')[0].selectedIndex;

        // Iterar sobre los elementos seleccionados para agregar
        videos_seleccionados = $('#pool_videos')[0].selectedOptions;
        for(let i = 0; i < videos_seleccionados.length; i++) {
            // Clonar instancia del item de la lista de la izquierda
            elemento = $(videos_seleccionados[i]).clone();

            // Si tengo algo seleccionado de aquel lado, inserto antes de lo seleccionado
            if (indice_insertar != -1) {
                $(elemento).insertBefore(
                    $($('#items_lista option')[indice_insertar])
                );
            } else {
                // Si no tenía nada seleccionado, simplemente append al final
                $('#items_lista').append(elemento);
            }
        }
    });

    $('#btn-quitar_video').on('click', function(e) {
        $($('#items_lista')[0].selectedOptions).remove();
        $('#items_lista').val([]).trigger('change');
    });

    $('#btn-desplazar_arriba').on('click', function(e) {
        indice_sel = $('#items_lista')[0].selectedIndex;
        elemento_sel = $('#items_lista')[0].selectedOptions[0];

        if (indice_sel == 0) {
            return;
        }

        $(elemento_sel).insertBefore(
            $($('#items_lista option')[indice_sel-1])
        );
    });
    
    $('#btn-desplazar_abajo').on('click', function(e) {
        indice_sel = $('#items_lista')[0].selectedIndex;
        elemento_sel = $('#items_lista')[0].selectedOptions[0];

        cant_elementos_lista = $('#items_lista option').length;

        if (indice_sel == cant_elementos_lista - 1) {
            return;
        }

        $(elemento_sel).insertAfter(
            $($('#items_lista option')[indice_sel+1])
        );
    });

    $('#pool_videos option').on('dblclick', function(e) {
        $('#play_' + $(this).val()).trigger('click');
    });

    $('#items_lista option').on('dblclick', function(e) {
        $('#play_' + $(this).val()).trigger('click');
    });
});
