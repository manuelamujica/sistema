



// extraen razon social y cod de proveedor  



$('#rif').blur(function(e) {
    var buscar = $('#rif').val();
    $.post('index.php?pagina=proveedores', {
        buscar
    }, function(response) {
        if (response != '') {
            alert('El proveedor ya esta registrado')
        }
    }, 'json');
});




$('#cedula').blur(function (e) {
    var buscar = $('#cedula').val();
    $.post('index.php?pagina=representantes', {
        buscar
    }, function (response) {
        if (response != '') {
            alert('El representante ya esta registrado')
        }
    }, 'json');
});



$('#telefono').blur(function (e) {
    var buscar = $('#telefono').val();
    $.post('index.php?pagina=tproveedores', {
        buscar
    }, function (response) {
        if (response != '') {
            alert('El telefono ya esta registrado')
        }
    }, 'json');
});





$('#myModalt').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Botón que abrió el modal
    var cod1 = button.data('cod1'); // Extraer datos de los atributos data-*
    var rif = button.data('rif');

    var modal = $(this);
    modal.find('.modal-body #cod1').val(cod1); // Asignar valores a los campos del modal
    modal.find('.modal-body #rif').val(rif);
});



//Edextrae pra representante itar
$('.mas').click(function (e) {
    if ($(this).hasClass('mas')) {
        e.preventDefault();
    }
});



//REPRESENTANTE REG

$(document).ready(function () {
    // Cuando se hace clic en el botón 
    $('.mas').click(function () {
        // Obtener los datos del botón
        var cod1 = $(this).data('cod1');

        // Asignar los valores al formulario del modal
        $('#cod1').val(cod1);
        $('#cod_oculto').val(cod1);

    });
});







//Editar falta 

$('#modalProvedit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var cod = button.data('cod');
    var rif = button.data('rif');
    var razon = button.data('razon');
    var email = button.data('email');
    var dire = button.data('dire');
    var status = button.data('status');
    var telefono = button.data('telefono');
    var origin = button.data('rif'); // Cambiado para usar 'origin' 

    // Modal
    var modal = $(this);
    modal.find('.modal-body #cod').val(cod);
    modal.find('.modal-body #rif').val(rif);
    modal.find('.modal-body #razon').val(razon);
    modal.find('.modal-body #email').val(email);
    modal.find('.modal-body #dire').val(dire);
    modal.find('.modal-body #status').val(status);
    modal.find('.modal-body #telefono').val(telefono);
    modal.find('.modal-body #origin').val(origin);
 
});






$('#Modale').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);

    var codigo = button.data('codigo');

    var modal = $(this);

    modal.find('.modal-body #provCodigo').val(codigo);
});

