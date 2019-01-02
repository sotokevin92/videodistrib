$(function() {
    $('.link_editar').on('click', function(e) {
        tituloModal('modal-gen', 'Editar datos de la pantalla');
        cargarModal(
            'modal-gen',
            '/pantalla/editar',
            { 'id': $(this).attr('data-id') }
        );
    });

    $('.link_eliminar').on('click', function(e) {
        tituloModal('modal-gen', 'Confirmar eliminar pantalla');
        cargarModal(
            'modal-gen',
            '/pantalla/eliminar',
            { 'id': $(this).attr('data-id') }
        );
    });

    $('.link_habilitar').on('click', function(e) {
        console.log($(this).attr('data-id'));
        $.post('/manager/toggle_pantalla', {'id': $(this).attr('data-id')}, function(d) {
            location.reload();
        });
    });

    $('.link_asignar').on('click', function(e) {
        tituloModal('modal-gen', 'Asignar lista al pantalla');
        cargarModal(
            'modal-gen',
            '/pantalla/asignar',
            { 'id': $(this).attr('data-id') }
        );
    });
});
