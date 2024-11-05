



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


//eliminar proveedor 

$('#Modale').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);

    var codigo = button.data('codigo');

    var modal = $(this);

    modal.find('.modal-body #provCodigo').val(codigo);
});


  




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


$('#rif1').blur(function(e) {
    var buscar = $('#rif1').val();
    $.post('index.php?pagina=proveedores', {
        buscar
    }, function(response) {
        if (response != '') {
            alert('El RIF  ya existe');
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

$('#cedula3').blur(function(e) {
    var buscar = $('#cedula3').val();
    $.post('index.php?pagina=representantes', {
        buscar
    }, function(response) {
        if (response != '') {
            alert('La cedula  ya existe');
        }
    }, 'json');
});





