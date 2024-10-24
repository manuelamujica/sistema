

//editar  represen funciona
   
$('#modalProveditr').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var cod = button.data('cod');
    var cedula = button.data('cedula');
    var nombre = button.data('nombre');
    var apellido = button.data('apellido');
    var telefono = button.data('telefono');
    var status = button.data('status');
  
    var origin = button.data('origin'); // Cambiado para usar 'origin' 

    // Modal
    var modal = $(this);
    modal.find('.modal-body #cod').val(cod);
    modal.find('.modal-body #cedula').val(cedula);
    modal.find('.modal-body #nombre').val(nombre);
    modal.find('.modal-body #apellido').val(apellido);
    modal.find('.modal-body #telefono').val(telefono);
    modal.find('.modal-body #status').val(status);
  

 
});

//eliminar y que salfa quien

$('#Modalel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var nombre = button.data('nombre');
    var codigo = button.data('codigo');

    var modal = $(this);
    modal.find('#repreNombre').text(nombre);
    modal.find('.modal-body #reprCodigo').val(codigo);
});
