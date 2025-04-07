console.log('abrio js');

//VALIDACIONES REGISTRAR
// FUNCIONES
function showError(selector, message) {
    $(selector).addClass('is-invalid');
    $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toUpperCase()).css({
        'display': 'block',
        'color': 'red',
    });
}

function hideError(selector) {
    $(selector).removeClass('is-invalid');
    $(selector).next('.invalid-feedback').css('display', 'none');
}


$('#nombre').on('blur', function() {
    var nombre = $(this).val();
    if (nombre.trim() === '') {
        hideError('#nombre');
    }else if (nombre.length > 50) {
        showError('#nombre', 'El texto no debe exceder los 50 caracteres'); // Validar longitud máxima
    } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nombre)) {
        showError('#nombre', 'Solo se permiten letras');
    } else {
        hideError('#nombre');
    }
});

$('#simbolo').on('blur', function() {
    var simbolo = $(this).val();
    if (simbolo.trim() === '') {
        hideError('#simbolo'); 
    }else if (simbolo.length > 5) {
        showError('#simbolo', 'El texto no debe exceder los 5 caracteres'); // Validar longitud máxima
    } else if (!/^[a-zA-ZÀ-ÿ\s\$\€]+$/.test(simbolo)) {
        showError('#simbolo', 'Solo se permiten letras, números, espacios y $, y €');
    } else {
        hideError('#simbolo');
    }
});

$('#tasa').on('input', function() {
    var tasa = $(this).val();
    if (tasa.trim() === '') {
        hideError('#tasa'); 
    } else if (!/^\d+(\.\d{1,2})?$/.test(tasa)) { // Permite números y un máximo de 2 decimales
        showError('#tasa', 'Solo se permiten números y 2 decimales opcional.');
    } else {
        hideError('#tasa'); 
    }
});

//VALIDACIONES EDITAR

$('#nombre1').on('blur', function() {
    var nombre1 = $(this).val();
    if (nombre1.trim() === '') {
        hideError('#nombre1');
    }else if (nombre1.length > 50) {
        showError('#nombre1', 'El texto no debe exceder los 50 caracteres'); // Validar longitud máxima
    } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nombre1)) {
        showError('#nombre1', 'Solo se permiten letras');
    } else {
        hideError('#nombre1');
    }
});


$('#abreviatura').on('blur', function() {
    var abreviatura = $(this).val();
    if (abreviatura.trim() === '') {
        hideError('#abreviatura'); 
    }else if (simbolo.length > 5) {
        showError('#abreviatura', 'El texto no debe exceder los 5 caracteres'); // Validar longitud máxima
    } else if (!/^[a-zA-ZÀ-ÿ\s\$\€]+$/.test(abreviatura)) {
        showError('#abreviatura', 'Solo se permiten letras, números, espacios y $, y €');
    } else {
        hideError('#abreviatura');
    }
});


$('#tasa1').on('input', function() {
    var tasa1 = $(this).val();
    if (tasa1.trim() === '') {
        hideError('#tasa1'); 
    } else if (!/^\d+(\.\d{1,2})?$/.test(tasa1)) { // Permite números y un máximo de 2 decimales
        showError('#tasa1', 'Solo se permiten números y 2 decimales opcional.');
    } else {
        hideError('#tasa1'); 
    }
});

//VALIDAR NOMBRE REGISTRO
$('#nombre').blur(function (e){
    var buscar=$('#nombre').val();
    $.post('index.php?pagina=divisa', {buscar}, function(response){
    if(response != ''){
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'La divida ya se encuentra registrada',
            confirmButtonText: 'Aceptar'
        });
    }
    },'json');
});
// VALIDAR NOMBRE EDITAR
$('#nombre1').blur(function (e){
    var buscar=$('#nombre1').val();
    $.post('index.php?pagina=divisa', {buscar}, function(response){
    if(response != ''){
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'La divisa ya se encuentra registrada',
            confirmButtonText: 'Aceptar'
        });
    }
    },'json');
});

//EDITAR
$('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codigo');
    var nombre = button.data('nombre');
    var origi = button.data('nombre');
    var abreviatura = button.data('abreviatura');
    var tasa = button.data('tasa');
    var status = button.data('status');

    // Modal
    var modal = $(this);
    modal.find('.modal-body #codigo').val(codigo);
    modal.find('.modal-body #nombre1').val(nombre);
    modal.find('.modal-body #abreviatura').val(abreviatura);
    modal.find('.modal-body #tasa1').val(tasa);
    modal.find('.modal-body #status').val(status);
    modal.find('.modal-body #origin').val(origi);
});

//ELIMINAR
$('#eliminardivisa').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var nombre = button.data('nombre');
    var codigo = button.data('codigo');

    var modal = $(this);
    modal.find('#divisaNombre').text(nombre);
    modal.find('.modal-body #divisaCodigo').val(codigo);
});