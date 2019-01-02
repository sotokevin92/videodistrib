$(function() {
    $('a.eliminar_lista').on('click', function(e) {
        mi_idlista = this.getAttribute('data-id');
        $('#eliminar_id').val(mi_idlista);
    });

    $('a.asignacion_pantallas').on('click', function(e) {
        mi_idlista = this.getAttribute('data-id');

        cargarModal('/lista/asignar',
            { 'lista': mi_idlista }
        );
    });
});

function cargarModal(url, data) {
    $.get(url, data, function(resp) {
        $('#modal-asignacion_pantallas .modal-body').html(resp);
    });
}
