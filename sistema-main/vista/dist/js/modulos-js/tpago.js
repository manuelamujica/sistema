$(document).ready(function() {
console.log('js');
$('#tipo_pago').blur(function (e){
    var buscar=$('#tipo_pago').val();
    $.post('index.php?pagina=tpago', {buscar}, function(response){
    if(response != ''){
        Swal.fire({
            title: 'Advertencia',
            text: 'El tipo de pago ya se encuentra registrado',
            icon: 'warning'
        });
    }
    },'json');
});

$('#tpago').blur(function (e){
    var buscar=$('#tpago').val();
    $.post('index.php?pagina=tpago', {buscar}, function(response){
    if(response != ''){
        Swal.fire({
            title: 'Advertencia',
            text: 'El tipo de pago ya se encuentra registrado',
            icon: 'warning'
        });
    }
    },'json');
});

$('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codigo');
    var tpago = button.data('medio');
    var status = button.data('status');
    var origin = button.data('medio');
    var nombre = button.data('nombre')+" - "+button.data('divisa');
    // Modal
    var modal = $(this);
    modal.find('.modal-body #codigo').val(codigo);
    modal.find('.modal-body #tpago').val(tpago);
    modal.find('.modal-body #status').val(status);
    modal.find('.modal-body #divisa1').val(nombre);
    modal.find('.modal-body #origin').val(origin);
});

$('#eliminartpago').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var medio = button.data('medio');
    var codigo = button.data('codigo');

    var modal = $(this);
    modal.find('#tpagonombre').text(medio);
    modal.find('.modal-body #tpagoCodigo').val(codigo);
});


// VALIDAR ENTRADAS

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
    // FIN FUNCIONES

    // Registrar
    $('#tipo_pago').on('blur', function() {
        var tipo_pago = $(this).val();
        if (tipo_pago.trim() === '') {
            hideError('#tipo_pago');
        }else if (tipo_pago.length > 50) {
            showError('#tipo_pago', 'El texto no debe exceder los 50 caracteres'); // Validar longitud máxima
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(tipo_pago)) {
            showError('#tipo_pago', 'Solo se permiten letras');
        } else {
            hideError('#tipo_pago');
        }
    });

    // Editar
    $('#tpago').on('blur', function() {
        var tpago = $(this).val();
        if (tpago.trim() === '') {
            hideError('#tpago');
        }else if (tpago.length > 50) {
            showError('#tpago', 'El texto no debe exceder los 50 caracteres'); // Validar longitud máxima
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(tpago)) {
            showError('#tpago', 'Solo se permiten letras');
        } else {
            hideError('#tpago');
        }
    });


});