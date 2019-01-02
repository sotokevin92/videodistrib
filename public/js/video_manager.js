$(function() {
    $('a.editar_video').on(
        'click', function(e) {
            tituloModal('modal-gen', 'Editar datos del video');
            cargarModal('modal-gen', '/video/editar',
                { 'id': this.getAttribute('data-id') }
            );
        }
    );
    
    $('a.eliminar_video').on(
        'click', function(e) {
            tituloModal('modal-gen', 'Atención');
            cargarModal('modal-gen', '/video/eliminar',
                { 'id': this.getAttribute('data-id') }
            );
        }
    );

    $('#frm-subir_video').on('submit', function(e) {
        $('#btn-subir_video').attr('disabled', true);
        $('#btn-subir_video').val('Cargando...');
    });

    $.each($('.progreso_video'), function(ind, elem) {
        (function poll() {
            setTimeout(function() {
                $.ajax({
                    url: location.origin + '/proceso/' + elem.getAttribute('data-id'),
                    type: "GET",
                    success: function(data) {
                        console.log(data);
                        $(elem).css('width', data + '%');
                    },
                    complete: poll,
                    timeout: 400
                })
            }, 500);
        })();
    });
});
