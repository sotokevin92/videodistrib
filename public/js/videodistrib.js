$(function() {
    url = window.location.origin + window.location.pathname;
    $('.sidebar-nav li a[href="' + url + '"]').parent().addClass('activo');

    $('.recargar').on('click', recargarPagina);
});

function tituloModal(idmodal, texto) {
    $('#' + idmodal + ' .modal-title').html(texto);
}

function cargarModal(idmodal, url, data) {
    $('#' + idmodal + ' .modal-body').html('');
    $.get(url, data, function(resp) {
        $('#' + idmodal + ' .modal-body').html(resp);
    });
}

function recargarPagina() {
    location.reload(true);
}