//Validar registrar
$('#numero_cuenta1').blur(function (e) {
    var buscar = $('#numero_cuenta1').val();
    $.post('index.php?pagina=cuentabancariacopia', { buscar }, function (response) {
        if (response != '') {
            Swal.fire({
                title: 'Error',
                text: 'El numero de cuenta ya se encuentra registrado.',
                icon: 'warning'
            });
        }
    }, 'json');
});


//VALIDACIÓN
$(document).ready(function () {
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

    $('#numerocuenta').on('blur', function() {
        var numero_cuenta = $(this).val();
        if(numero_cuenta.trim() === ''){
            hideError('#numerocuenta');
        }else if (numero_cuenta.length > 20) {
            showError('#numerocuenta', 'El numero de cuenta no debe exceder los 20 caracteres'); // Validar longitud máxima
        } else if (!/^[0-9]+$/.test(numero_cuenta)) {
            showError('#numerocuenta', 'Solo se permiten numeros');
        } 
    });
    $('#saldo').on('blur', function() {
        var saldo = $(this).val();
        if (saldo.trim() === '') {
            hideError('#saldo');
        } else if (saldo.length > 10) {
            showError('#saldo', 'El saldo no debe exceder los 10 caracteres');
        } else if (!/^[0-9.]+$/.test(saldo)) {
            showError('#saldo', 'Solo se permiten números');
        } else {
            hideError('#saldo');
        }
    });
    

});
 



$('.editar').click(function () {
    var cod = $(this).data('cod');
    var numero = $(this).data('numero'); 
    var saldo = $(this).data('saldo');
    var divisa = $(this).data('divisa');
    var status = $(this).data('status');
    var banco = $(this).data('banco');
    var tipocuenta = $(this).data('tipocuenta'); 

    // Asignar valores
    $('#cod_cuenta_bancaria_oculto').val(cod);
    $('#cod_cuenta_bancaria1').val(cod);
    $('#numero_cuenta1').val(numero);
    $('#saldo1').val(saldo);
    $('#divisa1').val(divisa);
    $('#status').val(status);
    $('#banco1').val(banco);
    $('#tipodecuenta1').val(tipocuenta);

    // Verificar si el valor de cod_cuenta_bancaria es correcto
    console.log('Código de cuenta bancaria:', cod);
});


//ELIMINAR
$('#modaleliminar').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var codigo = button.data('cod');
    var status = button.data('status');
    var numero = button.data('numero');

    var modal = $(this);
    modal.find('.modal-body #cod_eliminar').val(codigo);
    modal.find('#numero_cuentaD').text(numero);
    modal.find('.modal-body #status_e').val(codigo);

   
});




