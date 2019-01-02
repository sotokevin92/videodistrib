$('a.link_video').on(
    'click', function(e) {
        cargarModalVideo('/video/popup',
            { 'id': this.getAttribute('data-id') }
        );
    }
);

$('#modal-video').on('hidden.bs.modal', function(e) {
    $('#modal-video .modal-body').html('');
});

function cargarModalVideo(url, data) {
    $.get(url, data, function(resp) {
        $('#modal-video .modal-body').html(resp);
    });
}
