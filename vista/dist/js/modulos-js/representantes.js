$('#Modalel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var nombre = button.data('nombre');
    var codigo = button.data('codigo');

    var modal = $(this);
    modal.find('#repreNombre').text(nombre);
    modal.find('.modal-body #reprCodigo').val(codigo);
});
